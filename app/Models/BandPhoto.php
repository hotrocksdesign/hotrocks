<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BandPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'band_id',
        'photo_url',
        'caption',
        'order',
    ];

    public function band()
    {
        return $this->belongsTo(Band::class);
    }
}
