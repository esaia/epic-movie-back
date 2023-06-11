<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $genres = [
            'Action',
            'Adventure',
            'Comedy',
            'Drama',
            'Fantasy',
            'Horror',
        ];

        foreach ($genres as $genre) {
            Genre::factory()->create(['label' => $genre, 'value' => $genre]);
        }


        User::factory(3)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
