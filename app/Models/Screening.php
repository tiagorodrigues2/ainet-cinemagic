<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Screening extends Model
{
    use HasFactory;

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'screening_id', 'id');
    }

    public function theater()
    {
        return $this->belongsTo(Theater::class, 'theater_id', 'id');
    }

    public static function GetNextScreeningsByMovieID(int $movie_id) {
        $result = self::where('movie_id', $movie_id)
            ->join('theaters', 'screenings.theater_id', '=', 'theaters.id')
            ->select('screenings.id', 'theaters.name as theater', 'screenings.date', 'screenings.start_time')
            ->where('date', '>=', DB::raw('CURDATE()'))
            ->where('date', '<=', DB::raw('CURDATE() + INTERVAL 14 DAY'))
            ->orderBy('date')
            ->get();



        return $result->all();
    }
}
