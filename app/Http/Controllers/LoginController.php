<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;

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
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ], [
            'name.required' => 'The Name field is required.',
            'email.required' => 'The Email field is required.',
            'password.required' => 'The password field is required.',
            'password_confirmation.required' => 'The password confirmation field is required.',
            'password_confirmation.same' => 'The password confirmation does not match the password.',
        ]);

        $jaExiste = User::where('email', $request->email)->first();
        if ($jaExiste) {
            return back()->withErrors([
                'register' => 'Email already registered.',
            ])->withInput();
        }

        try {
            $user = User::create([
                'type' => 'C',
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            Auth::login($user, true);

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
        $error = Session::get('error');

        return view('auth.profile')->with('user', $user)->with('success', $success)->with('error', $error);
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
