<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class CostumersController extends Controller
{
    public function index(): View
    {
        if (!auth()->check()) {
            return view('errors.403');
        }

        $search = $_GET['search'] ?? null;

        if (!auth()->user()->isAdmin()) {
            return view('errors.403');
        }

        $costumers = User::where('type', 'C')
                ->when($search, function ($query) use ($search) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                })
                ->get();

        return view('costumers.costumer-list')->with('costumers', $costumers)->with('search', $search);
    }
}
