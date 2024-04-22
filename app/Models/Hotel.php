<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Hotel extends Model
{
    use HasFactory;

    protected $casts = [
        'foto' => 'array',
    ];

    protected $guarded = [
        'id'
    ];

    public function provinsi(): HasOne
    {
        return $this->hasOne(Provinsi::class, 'id', 'provinsi_id');
    }

    public function kabupaten(): HasOne
    {
        return $this->hasOne(Kabupaten::class, 'id', 'kota_id');
    }

    public function kamar(): HasMany
    {
        return $this->hasMany(Kamar::class);
    }
}
