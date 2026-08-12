<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Band extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'biography',
        'photo_url',
        'instagram_url',
        'spotify_url',
        'youtube_url',
        'genre',
        'slug',
        'is_approved',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function shows()
    {
        return $this->belongsToMany(Show::class, 'show_band');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function photos()
    {
        return $this->hasMany(BandPhoto::class)->orderBy('order');
    }

    /**
     * Resolve a list of typed band names to band IDs, creating any that
     * don't exist yet. Existing bands are matched by name (case-insensitive)
     * and left untouched; only newly created bands get $approved applied.
     */
    public static function resolveOrCreateMany(array $names, bool $approved = true): array
    {
        $ids = [];

        foreach ($names as $name) {
            $name = trim($name);

            if ($name === '') {
                continue;
            }

            $band = static::whereRaw('LOWER(name) = ?', [mb_strtolower($name)])->first();

            if (! $band) {
                $band = static::create([
                    'name' => $name,
                    'slug' => str($name)->slug(),
                    'is_approved' => $approved,
                ]);
            }

            $ids[] = $band->id;
        }

        return array_unique($ids);
    }
}
