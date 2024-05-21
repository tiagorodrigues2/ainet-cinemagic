<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'costumer_id', 'id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'purchase_id', 'id');
    }
}
