<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use App\Models\Configuration;

class LoginController extends Controller
{
    public function index(): View {
        return view('auth.login');
    }

    public function register(): View {
        return view('auth.register');
    }

    public function login(Request $request) {

        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            if (Auth::user()->blocked == true) {
                Auth::logout();
                return back()->withErrors([
                    'login' => 'Vai dar uma volta ao bilhar grande, pá!',
                ]);
            }

            $config = Configuration::find(1);
            $ticketPrice = $config->ticket_price - $config->registered_customer_ticket_discount;
            CartController::atualizaPrecosCarrinho($ticketPrice);

            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'login' => 'Invalid email or password.',
        ]);
    }

    public function logout(Request $request): RedirectResponse {
        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->intended('/');
        }
        return redirect('/');
    }

    public function registerUser(Request $request) {

        $request->validate([
            'name' => 'required|max:255|string',
            'email' => 'required|max:255|string',
            'password' => 'required|string',
            'password_confirmation' => 'required|same:password',
        ], [
            'name.required' => 'The Name field is required.',
            'name.max' => 'The Name field must have a maximum of 255 characters.',
            'name.string' => 'The Name field must be a string.',
            'email.required' => 'The Email field is required.',
            'email.max' => 'The Email field must have a maximum of 255 characters.',
            'email.string' => 'The Email field must be a string.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password field must be a string.',
            'password_confirmation.required' => 'The password confirmation field is required.',
            'password_confirmation.same' => 'The password confirmation does not match the password.',
        ]);

        $jaExiste = User::where('email', $request->email)->first();
        if ($jaExiste) {
            return back()->withErrors([
                'register' => 'Email already registered.',
            ])->withInput();
        }

        $config = Configuration::find(1);
        $ticketPrice = $config->ticket_price - $config->registered_customer_ticket_discount;

        try {
            $user = User::create([
                'type' => 'C',
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            $c = Customer::create([
                'id' => $user->id
            ]);

            Auth::login($user, true);

            CartController::atualizaPrecosCarrinho($ticketPrice);

            return redirect('/');
        } catch (\Exception $e) {
            return back()->withErrors([
            'register' => 'An error occurred while registering. Please try again.',
            ])->withInput();
        }
    }

    public function profile(): View {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $success = Session::get('success');
        $erro = Session::get('erro');

        if (Auth::user()->isCustomer()) {
            $customer = Customer::find($user->id);
            return view('auth.profile')->with('user', $user)->with('customer', $customer)->with('success', $success)->with('error', $erro);
        }

        return view('auth.profile')->with('user', $user)->with('success', $success)->with('erro', $erro);
    }

    public function saveProfile(Request $request): RedirectResponse {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (!$user->isCustomer()) {
            abort(403);
        }

        $customer = Customer::find($user->id);

        if (!$customer) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255',
            'nif' => 'max:9',
            'payment_type' => 'in:MBWAY,PAYPAL,VISA',
            'payment_ref' => 'max:255',
        ], [
            'name.required' => 'The Name field is required.',
            'email.required' => 'The Email field is required.',
            'nif.max' => 'The NIF field must have a maximum of 9 characters.',
            'payment_type.in' => 'The payment type must be one of: MBWAY, PAYPAL, VISA',
            'payment_ref.max' => 'The payment reference field must have a maximum of 255 characters.',
        ]);

        DB::beginTransaction();
        try {

            $user->name = $request->name;
            $user->email = $request->email;
            $user->save();

            $customer->payment_type = $request->payment_type;
            $customer->payment_ref = $request->payment_ref;
            $customer->nif = $request->nif;
            $customer->save();

            Session::flash('success', 'Profile saved successfully!');
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('erro', 'An error occurred while saving the profile. Please try again.');
        }
        return redirect()->route('profile');
    }

    public function savePhoto(Request $request): RedirectResponse {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = Auth::user();

        if ($request->hasFile('photo')) {
            try {
                $photo = $request->file('photo');
                $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('photos', $photoName, 'public');
                $user->photo_filename = str_replace('photos/', '', $photoPath);
                $user->save();

                Session::flash('success', 'Photo saved successfully!');

                return redirect()->route('profile');
            } catch (\Exception $e) {
                Session::flash('error', $e->getMessage());
                return redirect()->route('profile');
            }
        }

        Session::flash('error', 'Please select a photo.');
        return redirect()->route('profile');
    }

    public function savePassword(Request $request): RedirectResponse {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        if (!password_verify($request->current_password, $user->password)) {
            return back()->withErrors([
                'password' => 'Current password is incorrect.',
            ]);
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        Session::flash('success', 'Password saved successfully!');

        return redirect()->route('profile');
    }
}
