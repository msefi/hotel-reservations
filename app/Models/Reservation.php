<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function hotel(): HasOne
    {
        return $this->hasOne(Hotel::class, 'id', 'hotel_id');
    }

    public function room(): HasOne
    {
        return $this->hasOne(Kamar::class, 'id', 'kamar_id');
    }

    public function customer(): HasOne
    {
        return $this->hasOne(Customer::class, 'id', 'customer_id');
    }
}
