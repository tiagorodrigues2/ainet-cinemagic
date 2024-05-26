<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class CustomersController extends Controller
{
    public function index(): View
    {
        if (!(Auth::check() && Auth::user()->isAdmin() && !Auth::user()->blocked)) {
            abort(403, 'Unauthorized action.');
        }

        $sucesso = Session::get('success');
        $erro = Session::get('error');

        $search = $_GET['search'] ?? null;

        $customers = User::where('type', 'C')
                ->when($search, function ($query) use ($search) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                })
                ->get();

        return view('customers.customer-list')->with('customers', $customers)->with('search', $search)->with('sucesso', $sucesso)->with('erro', $erro);
    }

    public function delete(int $id): RedirectResponse {

        if (!(Auth::check() && Auth::user()->isAdmin() && !Auth::user()->blocked)) {
            abort(403, 'Unauthorized action.');
        }

        if (Auth::user()->id == $id) {
            abort(400);
        }

        $customer = User::find($id);

        if (!$customer) {
            return redirect()->route('customers')->with('error', 'Customer not found!');
        }

        $customer->delete();

        Session::flash('success', 'Customer ' . $customer->name . ' deleted successfully!');

        return redirect()->back()->with('success', 'Customer' . $customer->name . ' deleted successfully!');

    }

    public function toggleBlock(Request $request): RedirectResponse {

        if (!(Auth::check() && Auth::user()->isAdmin() && !Auth::user()->blocked)) {
            abort(403, 'Unauthorized action.');
        }

        $id = $request->all()['id'];

        if (!isset($id) || !is_numeric($id) || $id <= 0) {
            return redirect()->route('customers')->with('error', 'Customer not found!');
        }

        if (Auth::user()->id == $id) {
            abort(400);
        }

        $customer = User::find($id);

        if (!$customer) {
            return redirect()->route('customers')->with('error', 'Customer not found!');
        }

        $customer->blocked = !$customer->blocked;
        $customer->save();

        Session::flash('success', 'Customer ' . $customer->name . ($customer->blocked ? ' blocked' : ' unblocked') . ' successfully!');

        return redirect()->back()->with('success', 'Customer ' . $customer->name . ($customer->blocked ? ' blocked' : ' unblocked') . ' successfully!');
    }
}
