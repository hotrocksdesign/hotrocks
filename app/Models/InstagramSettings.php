<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'graph_api_token',
        'business_account_id',
        'account_username',
        'last_sync',
    ];

    protected $casts = [
        'last_sync' => 'datetime',
    ];

    public static function getActive()
    {
        return self::first();
    }
}
