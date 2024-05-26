<?php

namespace App\Http\Controllers;
use App\Models\Course;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Ticket;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Movie;
use App\Models\Screening;
use App\Models\Theater;
use App\Models\Seat;
use App\Models\Configuration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\Payment;
use Illuminate\Support\Facades\DB;
use App\Models\Costumer;


class CartController extends \Illuminate\Routing\Controller
{

    public function addToCart(Request $request) : RedirectResponse {

        $request->validate([
            'screening_id' => 'required|integer',
            'seat_id' => 'required|integer'
        ], [
            'screening_id.required' => 'Screening ID is required',
            'screening_id.integer' => 'Screening ID must be an integer',
            'seat_id.required' => 'Seat ID is required',
            'seat_id.integer' => 'Seat ID must be an integer'
        ]);

        $screening = Screening::find($request->input('screening_id'));
        $seat = Seat::find($request->input('seat_id'));
        $movie = $screening->movie()->first();
        $config = Configuration::find(1);

        if (!$screening || !$seat || !$movie || !$config) {
            abort(404);
        }

        // //Verificar se a screening não comecou já
        // $screeningDateTime = $screening->date . ' ' . $screening->time;
        // $now = date('Y-m-d H:i:s', time() + (5 * 60));
        // if ($screeningDateTime < $now) {
        //     Session::flash('error', 'Screening has already started');
        //     return redirect()->route('movie', ['id' => $movie->id]);
        // }

        $existingTicket = $screening->tickets()->where('seat_id', $seat->id)->first();

        if ($existingTicket) {
            $request->session()->flash('error', 'Seat is already taken');
            return redirect()->route('screening', ['id' => $screening->id]);
        }

        $ticketPrice = $config->ticket_price;
        if (Auth::check()) {
            $ticketPrice -= $config->registered_customer_ticket_discount;
        }

        $cart = Session::get('cart');

        if (!$cart) {
            $cart = [];
        }

        for ($i = 0; $i < count($cart); $i++) {
            if ($cart[$i]['screening_id'] == $screening->id && $cart[$i]['seat_id'] == $seat->id) {
                Session::flash('error', 'Seat is already in cart');
                return redirect()->route('screening', ['id' => $screening->id]);
            }
        }

        $new = [
            'movie' => $movie->title,
            'screening_date' => $screening->date . ' ' . $screening->time,
            'theater' => $screening->theater()->first()->name,
            'seat' => $seat->row . $seat->seat_number,
            'seat_id' => $seat->id,
            'screening_id' => $screening->id,
            'ticket_price' => $ticketPrice
        ];

        array_push($cart, $new);

        Session::put('cart', $cart);
        Session::flash('success', 'Ticket added to cart');

        return redirect()->route('movie', ['id' => $movie->id]);
    }

    public function checkout(): View {
        $cart = Session::get('cart');

        if (!$cart) {
            $cart = [];
        }

        $total = 0;

        foreach ($cart as $item) {
            $total += $item['ticket_price'];
        }

        return view('cart.checkout')->with('cart', $cart)->with('total', $total);
    }

    public function clearCart(): RedirectResponse {
        Session::forget('cart');
        Session::flash('success', 'Cart cleared');
        return redirect()->route('cart.checkout');
    }

    public function remove(int $seat_id): RedirectResponse {
        if (!is_int($seat_id)) {
            abort(404);
        }

        $cart = Session::get('cart');

        if (!$cart) {
            $cart = [];
        }

        for ($i = 0; $i < count($cart); $i++) {
            if ($cart[$i]['seat_id'] == $seat_id) {
                array_splice($cart, $i, 1);
                break;
            }
        }

        Session::put('cart', $cart);
        Session::flash('success', 'Ticket removed from cart');

        return redirect()->route('cart.checkout');
    }

    public function pay(Request $request): RedirectResponse {

        $cart = Session::get('cart');

        if (!$cart) {
            abort(404);
        }

        $request->validate([
            'tipo_pagamento' => 'required|string',
            'nif' => 'required|integer'
        ], [
            'tipo_pagamento.required' => 'Payment method is required',
            'tipo_pagamento.string' => 'Payment method must be a string',
            'nif.required' => 'NIF is required',
            'nif.integer' => 'NIF must be an integer'
        ]);

        if (!Auth::check()) {
            $request->validate([
                'customer_name' => 'required|string',
                'customer_email' => 'required|email',
            ], [
                'customer_name.required' => 'Name is required',
                'customer_name.string' => 'Name must be a string',
                'customer_email.required' => 'Email is required',
                'customer_email.email' => 'Email must be a valid email'
            ]);
        }

        $sucesso = false;
        $config = Configuration::find(1);
        $ticketPrice = $config->ticket_price;
        if (Auth::check()) {
            $ticketPrice -= $config->registered_customer_ticket_discount;
        }

        $total = $ticketPrice * count($cart);

        switch ($request->tipo_pagamento) {
            case 'cartao':
                $request->validate([
                    'visa_number' => 'required|integer',
                    'visa_cvv' => 'required|integer'
                ], [
                    'visa_number.required' => 'Card number is required',
                    'visa_number.integer' => 'Card number must be an integer',
                    'visa_cvv.required' => 'CVC code is required',
                    'visa_cvv.integer' => 'CVC code must be an integer'
                ]);
                $sucesso = Payment::payWithVisa($request->visa_number, $request->visa_cvv);
                break;
            case 'paypal':
                $request->validate([
                    'paypal_email' => 'required|email'
                ], [
                    'paypal_email.required' => 'Email is required',
                    'paypal_email.email' => 'Email must be a valid email'
                ]);
                $sucesso = Payment::payWithPaypal($request->paypal_email);
                break;
            case 'mbway':
                $request->validate([
                    'mbway_phone' => 'required|integer'
                ], [
                    'mbway_phone.required' => 'Phone number is required',
                    'mbway_phone.integer' => 'Phone number must be an integer'
                ]);
                $sucesso = Payment::payWithMBway($request->mbway_phone);
                break;
            default:
                Session::flash('error', 'Invalid payment method');
                return redirect()->route('cart.checkout');
        }

        if (!$sucesso) {
            Session::flash('error', 'Payment failed');
            return redirect()->route('cart.checkout');
        }

        $costumer =  Auth::check() ? Customer::find(Auth::user()->id) : null;
        $user = Auth::user();

        DB::beginTransaction();
        try {

            if ($costumer) {
                $costumer->nif = $request->nif;
                $costumer->payment_type = self::GetDB_PaymentMethod($request->tipo_pagamento);
                $costumer->payment_ref = self::GetPaymentRefByMethod($costumer->payment_type, $request);
                $costumer->save();
            }

            $purchase = new Purchase();
            $purchase->customer_id = $costumer ? $costumer->id : null;
            $purchase->date = date('Y-m-d');
            $purchase->total_price = $total;
            $purchase->customer_name = $user ? $user->name : $request->customer_name;
            $purchase->customer_email = $user ? $user->email : $request->customer_email;
            $purchase->nif = $request->nif;
            $purchase->payment_type = self::GetDB_PaymentMethod($request->tipo_pagamento);
            $purchase->payment_ref = self::GetPaymentRefByMethod($purchase->payment_type, $request);
            $purchase->save();

            foreach ($cart as $item) {
                $ticket = new Ticket();
                $ticket->purchase_id = $purchase->id;
                $ticket->screening_id = $item['screening_id'];
                $ticket->seat_id = $item['seat_id'];
                $ticket->price = $ticketPrice;
                $ticket->save();
            }

            DB::commit();
            Session::flash('success', 'Checkout successful');
            Session::forget('cart');
            return $user ? redirect()->route('purchases') : redirect()->route('home');
        } catch (\Exception $e) {
            dd($e);
            DB::rollback();
            Session::flash('error', 'Checkout Failed');
            return redirect()->route('cart.checkout');
        }
    }

    private static function GetDB_PaymentMethod(string $fromForm): string {
        switch ($fromForm) {
            case 'cartao':
                return 'VISA';
            case 'paypal':
                return 'PAYPAL';
            case 'mbway':
                return 'MBWAY';
            default:
                return null;
        }
    }

    private static function GetPaymentRefByMethod(string $payment_type, Request $request): string {
        switch ($payment_type) {
            case 'VISA':
                return $request->visa_number;
            case 'PAYPAL':
                return $request->paypal_email;
            case 'MBWAY':
                return $request->mbway_phone;
            default:
                return null;
        }
    }
}
