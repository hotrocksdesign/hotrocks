<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    use HasFactory;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'band_id',
        'user_id',
        'date',
        'venue',
        'city',
        'description',
        'flyer_url',
        'ticket_url',
        'status',
        'rejection_reason',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    public function bands()
    {
        return $this->belongsToMany(Band::class, 'show_band');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }
}
