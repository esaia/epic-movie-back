<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Movie>
 */
class MovieFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		return [
			'user_id'     => User::where('name', 'unique')->first(),
			'title'       => ['ka' =>  fake()->sentence(), 'en'=> fake()->sentence()],
			'date'        => fake()->date(),
			'director'    => ['ka' =>  fake()->name(), 'en'=> fake()->name()],
			'description' => ['ka' =>  fake()->paragraph(), 'en'=> fake()->paragraph()],
			'img'         => '/public/quotes/' . fake()->image(public_path('/storage/public/quotes/'), 640, 480, 'nature', false),
		];
	}
}
