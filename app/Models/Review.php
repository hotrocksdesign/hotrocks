<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'band_id',
        'show_id',
        'venue',
        'show_date',
        'featured_image',
        'setlist_image',
        'video_url',
        'user_id',
        'published_at',
        'is_featured',
    ];

    protected $casts = [
        'show_date' => 'datetime',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    public function band()
    {
        return $this->belongsTo(Band::class);
    }

    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(ReviewPhoto::class)->orderBy('order');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'review_tag');
    }
}
