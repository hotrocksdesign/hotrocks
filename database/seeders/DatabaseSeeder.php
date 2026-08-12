<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Band;
use App\Models\Tag;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear admin
        User::firstOrCreate([
            'email' => 'admin@hotrocks.local',
        ], [
            'name' => 'Administrador',
            'password' => bcrypt('admin123'),
            'role' => User::ROLE_ADMIN,
        ]);

        // Crear editor
        User::firstOrCreate([
            'email' => 'editor@hotrocks.local',
        ], [
            'name' => 'Editor',
            'password' => bcrypt('editor123'),
            'role' => User::ROLE_EDITOR,
        ]);

        // Crear bandas de ejemplo
        $bands = [
            ['name' => 'The Beatles', 'genre' => 'Rock'],
            ['name' => 'Pink Floyd', 'genre' => 'Progressive Rock'],
            ['name' => 'Led Zeppelin', 'genre' => 'Hard Rock'],
            ['name' => 'Queen', 'genre' => 'Rock'],
            ['name' => 'David Bowie', 'genre' => 'Alternative Rock'],
        ];

        foreach ($bands as $band) {
            Band::firstOrCreate(
                ['name' => $band['name']],
                [
                    'slug' => str($band['name'])->slug(),
                    'genre' => $band['genre'],
                ]
            );
        }

        // Crear tags de ejemplo
        $tags = [
            ['name' => 'Rock', 'type' => 'genre'],
            ['name' => 'Metal', 'type' => 'genre'],
            ['name' => 'Punk', 'type' => 'genre'],
            ['name' => 'Buenos Aires', 'type' => 'city'],
            ['name' => 'CABA', 'type' => 'city'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['name' => $tag['name']],
                [
                    'slug' => str($tag['name'])->slug(),
                    'type' => $tag['type'],
                ]
            );
        }
    }
}
