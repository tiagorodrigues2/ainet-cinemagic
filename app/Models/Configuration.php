<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $table = 'configuration';
    public $timestamps = false;

    protected $fillable = [
        'ticket_price',
        'registered_customer_ticket_discount',
    ];


}
