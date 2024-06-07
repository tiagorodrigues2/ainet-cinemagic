<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Theater extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['name', 'photo_filename'];

    public $timestamps = false;

    public function screenings()
    {
        return $this->hasMany(Screening::class, 'theater_id', 'id');
    }

    public function seats()
    {
        return $this->hasMany(Seat::class, 'theater_id', 'id');
    }
}
