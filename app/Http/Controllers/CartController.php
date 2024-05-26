<?php

namespace App\Http\Controllers;
use App\Models\Course;
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

        return view('cart.checkout')->with('cart', $cart);
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
}
