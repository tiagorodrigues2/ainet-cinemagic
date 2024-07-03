<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Purchase;
use App\Models\Screening;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class PurchasesController extends Controller {

    public function index(): View {
        if (!Auth::check()) {
            abort(403);
        }

        $user = Auth::user();

        if ($user) {
            $purchases = Purchase::where('customer_id', $user->id)->orderByDesc('date')->get();
        } else {
            $purchases = [];
        }

        return view('purchases.list')->with('purchases', $purchases);
    }

}
