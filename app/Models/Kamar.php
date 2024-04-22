<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    use HasFactory;

    protected $casts = [
        'foto'      => 'array',
        'fasilitas' => 'array',
    ];

    protected $guarded = [
        'id'
    ];
}
