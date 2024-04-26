<?php

namespace App\Models;

use App\Models\Custom\Poster;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Movie extends Model
{
    use HasFactory;
    use SoftDeletes;

    public static function GetPosters()
    {
        $posters = Movie::query()->join('genres', 'movies.genre_code', '=', 'genres.code')
            ->select('movies.id', 'movies.title', 'genres.name as genre', 'movies.poster_filename')
            ->orderBy('movies.title')
            ->get();
        return $posters;
    }

    public static function GetPostersInShow()
    {
        $posters = Movie::query()
            ->join('genres', 'movies.genre_code', '=', 'genres.code')
            ->select('movies.id', 'movies.title', 'genres.name as genre', 'movies.poster_filename')
            ->whereIn('movies.id', function ($query) {
                $query->select('movie_id')
                    ->from('screenings')
                    ->where('date', '>=', DB::raw('CURDATE()'))
                    ->where('date', '<=', DB::raw('CURDATE() + INTERVAL 14 DAY'));
            })
            ->orderBy('movies.title')
            ->get();

        return $posters;
    }
}
