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

    public function screenings()
    {
        return $this->hasMany(Screening::class, 'movie_id', 'id');
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class, 'genre_code', 'code');
    }

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
        $query = Movie::query()
            ->join('genres', 'movies.genre_code', '=', 'genres.code')
            ->select('movies.id', 'movies.title', 'genres.name as genre', 'movies.poster_filename')
            ->whereIn('movies.id', function ($query) {
                $query->select('movie_id')
                    ->from('screenings')
                    ->where('date', '>=', DB::raw('CURDATE()'))
                    ->where('date', '<=', DB::raw('CURDATE() + INTERVAL 14 DAY'));
            })
            ->orderBy('movies.title');

        $posters = $query->get();

        return $posters;
    }

    public static function GetMovieWithGenre(int $id) {
        $query = Movie::query()
            ->join('genres', 'movies.genre_code', '=', 'genres.code')
            ->select('movies.id', 'movies.title', 'genres.name as genre', 'movies.poster_filename', 'movies.synopsis', 'movies.trailer_url', 'movies.poster_filename')
            ->where('movies.id', $id);

        $result = $query->get()->first();
        return $result;
    }

    public static function GetMoviesWithGenre(int $page, int $itemsPerPage) {
        $query = Movie::query()
            ->join('genres', 'movies.genre_code', '=', 'genres.code')
            ->select('movies.id', 'movies.title', 'genres.name as genre', 'movies.poster_filename')
            ->orderBy('movies.title')
            ->offset(($page - 1) * $itemsPerPage)
            ->limit($itemsPerPage);

        $result = $query->get();
        return $result;
    }

    public static function GetMoviesWithGenresBySearch(string $search, int $page, int $itemsPerPage) {
        $query = Movie::query()
            ->join('genres', 'movies.genre_code', '=', 'genres.code')
            ->select('movies.id', 'movies.title', 'genres.name as genre', 'movies.poster_filename')
            ->whereRaw("LOWER(movies.title) LIKE '%" . strtolower($search) . "%'")
            ->orderBy('movies.title')
            ->offset(($page - 1) * $itemsPerPage)
            ->limit($itemsPerPage);

        $result = $query->get();
        return $result;
    }

    public static function GetMoviesWithGenresBySearchWithSynopsis(string $search, int $page, int $itemsPerPage) {
        $query = Movie::query()
            ->join('genres', 'movies.genre_code', '=', 'genres.code')
            ->select('movies.id', 'movies.title', 'genres.name as genre', 'movies.poster_filename')
            ->whereRaw("LOWER(movies.title) LIKE '%".strtolower($search)."%'")
            ->orWhereRaw("lower(movies.synopsis) LIKE '%".strtolower($search)."%'")
            ->orderBy('movies.title')
            ->offset(($page - 1) * $itemsPerPage)
            ->limit($itemsPerPage);

        $result = $query->get();
        return $result;
    }
}
