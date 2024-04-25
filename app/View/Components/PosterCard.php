<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PosterCard extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        private $movie
    )
    {
        if (is_null($this->movie)) {
            $this->movie->title = 'No title';
            $this->movie->genre = 'No genre';
            $this->movie->poster_filename = '_no_poster_1.png';
        }

        if (empty($this->movie->poster_filename)) {
            $this->movie->poster_filename = '_no_poster_1.png';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.poster')->with('movie', $this->movie);
    }
}
