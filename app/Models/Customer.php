<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'customer_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
