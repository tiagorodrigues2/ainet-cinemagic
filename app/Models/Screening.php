<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Screening extends Model
{
    use HasFactory;

    public static function GetNextScreeningsByMovieID(int $movie_id) {
        $result = self::where('movie_id', $movie_id)
            ->join('theaters', 'screenings.theater_id', '=', 'theaters.id')
            ->select('screenings.id', 'theaters.name as theater', 'screenings.date', 'screenings.start_time')
            ->where('date', '>=', 'NOW()')
            ->orderBy('date')
            ->get();

        return $result->all();
    }
}
