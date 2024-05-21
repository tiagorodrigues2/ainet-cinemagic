<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Screening;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class MoviesController extends Controller {

    public function movie(int $id): View {

        $movie = Movie::GetMovieWithGenre($id);
        $config = Configuration::find(1);

        if ($movie == null) {
            abort(404);
        }

        if (!isset($movie->poster_filename) || empty($movie->poster_filename)) {
            $movie->poster_filename = '_no_poster_1.png';
        }

        if (isset($movie->trailer_url) && !empty($movie->trailer_url)) {
            $movie->trailer_url = str_replace('https://www.youtube.com/watch?v=', '', $movie->trailer_url);
        }

        $nextScreenings = $movie->screenings()->where('date', '<=', DB::raw('CURDATE() + INTERVAL 14 DAY'))->where('date', '>=', DB::raw('CURDATE()'))->orderBy('date', 'asc')->get();
        $ticketPrice = $config->ticket_price;

        if (Auth::check()) {
            $ticketPrice -= $config->registered_costumer_ticket_discount;
        }

        return view('movies.movie')->with('movie', $movie)->with('nextScreenings', $nextScreenings)->with('ticketPrice', $ticketPrice);
    }

    public function movies(): View | RedirectResponse {

        $itemsPerPage = $_GET['items'] ?? 10;
        $search = $_GET['search'] ?? null;
        $synopsis = $_GET['synopsis'] ?? null;
        $page = $_GET['page'] ?? 1;

        if ($search) {

            if ($synopsis != null && $synopsis == true) {
                $movies = Movie::GetMoviesWithGenresBySearchWithSynopsis($search, $page, $itemsPerPage);
            }
            else {
                $movies = Movie::GetMoviesWithGenresBySearch($search, $page, $itemsPerPage);
            }

            if ($movies->count() == 0 && $page > 1) {
                return redirect()->route('movies', ['page' => 1, 'search' => $search]);
            }

            for ($i = 0; $i < $movies->count(); $i++) {
                if (!isset($movies[$i]->poster_filename) || empty($movies[$i]->poster_filename)) {
                    $movies[$i]->poster_filename = '_no_poster_1.png';
                }
            }

            return view('movies.list')->with('movies', $movies)->with('search', $search)->with('synopsis', $synopsis)->with('page', $page)->with('items', $itemsPerPage);
        }

        $movies = Movie::GetMoviesWithGenre($page, $itemsPerPage);

        if ($movies->count() == 0 && $page > 1) {
            return redirect()->route('movies', ['page' => 1]);
        }

        for ($i = 0; $i < $movies->count(); $i++) {
            if (!isset($movies[$i]->poster_filename) || empty($movies[$i]->poster_filename)) {
                $movies[$i]->poster_filename = '_no_poster_1.png';
            }
        }

        return view('movies.list')->with('movies', $movies)->with('search', $search)->with('synopsis', $synopsis)->with('page', $page)->with('items', $itemsPerPage);
    }
}
