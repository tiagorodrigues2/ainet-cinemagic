<?php

namespace App\Models;

use App\Models\Custom\Poster;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    public static function GetPosters() {
        $posters = \DB::select('select movies.id, movies.title, genres.name as genre, movies.poster_filename from movies join genres on movies.genre_code = genres.code');
        return $posters;
    }

    public static function GetPostersInShow() {
        $posters = \DB::select('select movies.id, movies.title, genres.name as genre, movies.poster_filename from movies join genres on movies.genre_code = genres.code where movies.id in (
            select movie_id from screenings where date >= CURDATE() and date <= CURDATE() + INTERVAL 14 DAY
        )');
        return $posters;
    }
}
