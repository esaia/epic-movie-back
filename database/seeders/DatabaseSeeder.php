<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Genre;
use App\Models\Movie;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	public function run(): void
	{
		$genres = config('genres');

		foreach ($genres as $genre) {
			Genre::factory()->create([
				'value' => $genre['value'],
				'label' => $genre['label'],
			]);
		}

		User::factory()->create(['name' => 'unique', 'password' => '123123123']);
		User::factory(2)->create(['password' => '123123123']);

		Movie::factory()->create();

		Quote::factory(2)->create();
	}
}
