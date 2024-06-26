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

class ScreeningController extends \Illuminate\Routing\Controller
{
    public function screening(int $id): View {

        $config = Configuration::find(1);
        $screening = Screening::find($id);

        if (!$screening) {
            abort(404);
        }

        $movie = $screening->movie()->first();
        $theater = $screening->theater()->first();
        $seats = $theater->seats()->orderBy('row')->orderBy('seat_number')->get();
        $tickets = $screening->tickets()->get();

        if (!isset($movie->poster_filename) || empty($movie->poster_filename)) {
            $movie->poster_filename = '_no_poster_1.png';
        }

        if (isset($movie->trailer_url) && !empty($movie->trailer_url)) {
            $movie->trailer_url = str_replace('https://www.youtube.com/watch?v=', '', $movie->trailer_url);
        }

        $cart = Session::get('cart', []);

        $seats = $seats->map(function ($seat) use ($tickets, $cart) {

            $ticket = $tickets->firstWhere('seat_id', $seat->id);
            $cartItem = collect($cart)->firstWhere('seat_id', $seat->id);

            if ($ticket) {
                $seat->status = 'occupied';
            } else if ($cartItem) {
                $seat->status = 'reserved';
            } else {
                $seat->status = 'free';
            }

            return $seat;
        });

        $seatRows = $seats->groupBy('row');

        return view('screening.buy')
            ->with('screening', $screening)
            ->with('movie', $movie)
            ->with('theater', $theater)
            ->with('seats', $seats)
            ->with('seatRows', $seatRows);
    }
}
