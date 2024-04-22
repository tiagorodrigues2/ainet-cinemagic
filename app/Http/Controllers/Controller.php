<?php

namespace App\Http\Controllers;
use App\Models\Course;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Movie;

class Controller extends \Illuminate\Routing\Controller
{
    public function index(): View
    {
        $movies = Movie::all();
        return view('home')->with('movies', $movies);
    }
}
