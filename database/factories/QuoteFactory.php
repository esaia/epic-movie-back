<?php

namespace Database\Factories;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quote>
 */
class QuoteFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'quote'           => ['ka' =>  fake()->sentence(), 'en'=> fake()->sentence()],
			'movie_id'        => Movie::factory(),
			'user_id'         => User::where('name', 'unique')->first(),
			'img'             => '/public/quotes/' . fake()->image(public_path('/storage/public/quotes/'), 640, 480, 'nature', false),
		];
	}
}
