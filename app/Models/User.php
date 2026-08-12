<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_EDITOR = 'editor';
    const ROLE_BAND = 'band';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'band_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isEditor()
    {
        return $this->role === self::ROLE_EDITOR;
    }

    public function isBand()
    {
        return $this->role === self::ROLE_BAND;
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function shows()
    {
        return $this->hasMany(Show::class);
    }

    public function band()
    {
        return $this->belongsTo(Band::class);
    }
}
