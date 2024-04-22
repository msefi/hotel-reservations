<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kabupaten extends Model
{
    use HasFactory;

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public static function Autocomplete($keyword) {
        $data   = self::select('name as value', 'id')->where('name', 'LIKE', '%' . $keyword . '%')->get();

        return $data;
    }
}
