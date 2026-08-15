<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    const MAX_FEATURED = 5;

    protected $fillable = [
        'title',
        'content',
        'band_id',
        'show_id',
        'venue',
        'show_date',
        'featured_image',
        'photo_credit',
        'setlist_image',
        'video_url',
        'user_id',
        'published_at',
        'is_featured',
        'featured_at',
    ];

    protected $casts = [
        'show_date' => 'datetime',
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
        'featured_at' => 'datetime',
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

    /**
     * Keeps at most MAX_FEATURED reviews featured at once — whichever were
     * marked featured longest ago get bumped off automatically when a new
     * one pushes the count over the cap.
     */
    public static function enforceFeaturedCap(): void
    {
        $ids = static::where('is_featured', true)
            ->orderByDesc('featured_at')
            ->pluck('id');

        if ($ids->count() > self::MAX_FEATURED) {
            static::whereIn('id', $ids->slice(self::MAX_FEATURED))
                ->update(['is_featured' => false, 'featured_at' => null]);
        }
    }
}
