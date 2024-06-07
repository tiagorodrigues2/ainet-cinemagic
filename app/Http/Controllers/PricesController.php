<?php

namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Movie;
use Illuminate\Support\Facades\Auth;
use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PricesController extends \Illuminate\Routing\Controller
{
    public function index(): View
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return abort(403);
        }

        $config = Configuration::find(1);

        return view('prices.index')->with('config', $config);
    }

    public function save(Request $request) {

        if (!Auth::check() || !Auth::user()->isAdmin()) {
            return abort(403);
        }

        $request->validate([
            'ticket_price' => 'required|numeric|min:0.01',
            'registered_customer_ticket_discount' => 'required|numeric|min:0|max:' . $request->input('ticket_price'),
        ], [
            'ticket_price.required' => 'The ticket price is required',
            'ticket_price.numeric' => 'The ticket price must be a number',
            'ticket_price.min' => 'The ticket price must be at least 0.01',
            'registered_customer_ticket_discount.required' => 'The registered customer ticket discount is required',
            'registered_customer_ticket_discount.numeric' => 'The registered customer ticket discount must be a number',
            'registered_customer_ticket_discount.min' => 'The registered customer ticket discount must be at least 0',
            'registered_customer_ticket_discount.max' => 'The registered customer ticket discount must be less than the ticket price',
        ]);

        $config = Configuration::find(1);

        $config->ticket_price = $request->input('ticket_price');
        $config->registered_customer_ticket_discount = $request->input('registered_customer_ticket_discount');
        $config->save();

        Session::flash('success', 'Prices updated successfully!');

        return redirect()->route('prices');

    }
}
