<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class CostumersController extends Controller
{
    public function index(): View
    {
        if (!(Auth::check() && Auth::user()->isAdmin())) {
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

        $costumers = User::where('type', 'C')
                ->when($search, function ($query) use ($search) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                })
                ->get();

        return view('costumers.costumer-list')->with('costumers', $costumers)->with('search', $search)->with('sucesso', $sucesso)->with('erro', $erro);
    }

    public function delete(int $id): RedirectResponse {

        if (!(Auth::check() && Auth::user()->isAdmin())) {
            abort(403, 'Unauthorized action.');
        }

        $costumer = User::find($id);

        if (!$costumer) {
            return redirect()->route('costumers')->with('error', 'Costumer not found!');
        }

        $costumer->delete();

        return redirect()->route('costumers')->with('success', 'Costumer' . $costumer->name . ' deleted successfully!');
    }

    public function toggleBlock(Request $request): RedirectResponse {
        if (!(Auth::check() && Auth::user()->isAdmin())) {
            abort(403, 'Unauthorized action.');
        }

        $id = $request->all()['id'];

        if (!isset($id) || !is_numeric($id) || $id <= 0) {
            return redirect()->route('costumers')->with('error', 'Costumer not found!');
        }

        $costumer = User::find($id);

        if (!$costumer) {
            return redirect()->route('costumers')->with('error', 'Costumer not found!');
        }

        $costumer->blocked = !$costumer->blocked;
        $costumer->save();

        return redirect()->route('costumers')->with('success', 'Costumer ' . $costumer->name . ($costumer->blocked ? ' blocked' : ' unblocked') . ' successfully!');
    }
}
