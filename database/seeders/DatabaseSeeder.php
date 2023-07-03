<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class DatabaseSeeder extends Seeder
{
	public function run(): void
	{
		$genres = config('genres');

		foreach ($genres as $genre) {
			Log::info($genre);

			Genre::factory()->create([
				'value' => $genre['value'],
				'label' => $genre['label'],
			]);
		}

		// User::factory(3)->create();

		// \App\Models\User::factory()->create([
		//     'name' => 'Test User',
		//     'email' => 'test@example.com',
		// ]);
	}
}
