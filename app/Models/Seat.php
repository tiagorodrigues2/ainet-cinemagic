<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'theater_id',
        'row',
        'seat_number',
    ];
    public $timestamps = false;

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'seat_id', 'id');
    }

    public function theater()
    {
        return $this->belongsTo(Theater::class, 'theater_id', 'id');
    }
}
