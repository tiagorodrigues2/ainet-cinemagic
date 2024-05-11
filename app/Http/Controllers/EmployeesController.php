<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;


class EmployeesController extends Controller
{
    //

    public function index(): View
    {
        if (!(Auth::check() && Auth::user()->isAdmin() && !Auth::user()->blocked)) {
            abort(403, 'Unauthorized action.');
        }

        $sucesso = Session::get('success');
        $erro = Session::get('error');

        if (isset($sucesso)) {
            Session::forget('success');
        }

        if (isset($erro)) {
            Session::forget('error');
        }

        $search = $_GET['search'] ?? null;

        $employees = User::whereIn('type', ['E', 'A'])
            ->when($search, function ($query) use ($search) {
                $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
            })
            ->orderBy('type')
            ->orderBy('blocked', 'asc')
            ->orderBy('name')
            ->get();

        return view('employees.employees-list')->with('employees', $employees)->with('search', $search)->with('sucesso', $sucesso)->with('erro', $erro);
    }

    public function create(): View {
        if (!(Auth::check() && Auth::user()->isAdmin() && !Auth::user()->blocked)) {
            abort(403, 'Unauthorized action.');
        }

        return view('employees.employees-create');
    }

    public function show(int $id): View | RedirectResponse {
        if (!(Auth::check() && Auth::user()->isAdmin() && !Auth::user()->blocked)) {
            abort(403, 'Unauthorized action.');
        }

        if (!$id) {
            return redirect()->route('employees');
        }

        $employee = User::find($id);

        if (!$employee || $employee->type == 'C') {
            return redirect()->route('employees')->withErrors([
                'employee' => 'Employee not found.',
            ]);
        }

        $sucesso = Session::get('success');
        $erro = Session::get('error');

        $isAtual = Auth::user()->id == $id;

        return view('employees.employees-show')->with('employee', $employee)->with('sucesso', $sucesso)->with('erro', $erro)->with('isAtual', $isAtual);
    }


    public function register(Request $request): View | RedirectResponse {
        if (!(Auth::check() && Auth::user()->isAdmin() && !Auth::user()->blocked)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'type' => 'required|in:A,E'
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'password.required' => 'Password is required',
            'type.required' => 'Type is required',
            'type.in' => 'Type is invalid'
        ]);
        try {

            $jaExiste = User::where('email', $request->email)->first();
            if ($jaExiste) {
                return back()->withErrors([
                    'register' => 'Email already registered.',
                ])->withInput();
            }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->type = $request->type;
            $user->photo_filename = null;

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('photos', $photoName, 'public');
                $user->photo_filename = str_replace('photos/', '', $photoPath);
            }


            User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'type' => $request->type,
                'photo_filename' => $user->photo_filename
            ]);

            Session::flash('success', 'Employee registered successfully!');

            return redirect()->route('employees');
        }
        catch (\Exception $e) {
            return back()->withErrors([
                'register' => $e->getMessage(),
            ])->withInput();
        }

    }

    public function save(Request $request): RedirectResponse {
        if (!(Auth::check() && Auth::user()->isAdmin() && !Auth::user()->blocked)) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'type' => 'required|in:A,E'
        ], [
            'id.required' => 'ID is required',
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'type.required' => 'Type is required',
            'type.in' => 'Type is invalid'
        ]);

        if (Auth::user()->id == $request->id) {
            abort(400);
        }

        try {
            $user = User::find($request->id);

            if (!$user) {
                return back()->withErrors([
                    'register' => 'Employee not found.',
                ])->withInput();
            }

            $user->name = $request->name;
            $user->email = $request->email;
            $user->type = $request->type;

            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $photoName = uniqid() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('photos', $photoName, 'public');
                $user->photo_filename = str_replace('photos/', '', $photoPath);
            }

            $user->save();

            Session::flash('success', 'Employee updated successfully!');

            return redirect()->route('employees.show', ['id' => $request->id]);
        }
        catch (\Exception $e) {
            return back()->withErrors([
                'register' => $e->getMessage(),
            ])->withInput();
        }
    }
}
