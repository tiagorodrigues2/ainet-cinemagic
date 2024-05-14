<?php

namespace App\Http\Controllers;

use App\Models\Screening;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use App\Models\Movie;

class MoviesController extends Controller {

    public function movie(int $id): View {

        $movie = Movie::GetMovieWithGenre($id);

        if ($movie == null) {
            abort(404);
        }

        if (!isset($movie->poster_filename) || empty($movie->poster_filename)) {
            $movie->poster_filename = '_no_poster_1.png';
        }

        if (isset($movie->trailer_url) && !empty($movie->trailer_url)) {
            $movie->trailer_url = str_replace('https://www.youtube.com/watch?v=', '', $movie->trailer_url);
        }

        $nextScreenings = Screening::GetNextScreeningsByMovieID($movie->id);
        return view('movies.movie')->with('movie', $movie)->with('nextScreenings', $nextScreenings);
    }
}
