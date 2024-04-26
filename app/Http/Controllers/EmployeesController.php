<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;


class EmployeesController extends Controller
{
    //

    public function index(): \Illuminate\View\View
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return view('errors.403');
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

        $employees = User::where('type', 'E')
                ->when($search, function ($query) use ($search) {
                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($search) . '%']);
                })
                ->get();

        return view('employees.employees-list')->with('employees', $employees)->with('search', $search)->with('sucesso', $sucesso)->with('erro', $erro);
    }
}
