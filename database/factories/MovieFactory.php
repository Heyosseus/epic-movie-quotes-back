<?php

namespace Database\Factories;

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
			'title' => json_encode([
				'en' => $this->faker->sentence(3),
				'ka' => $this->faker->sentence(3),
			]),
			'release_date' => $this->faker->date(),
			'description'  => json_encode([
				'en' => $this->faker->paragraph(3),
				'ka' => $this->faker->paragraph(3),
			]),
			'poster'   => $this->faker->imageUrl(),
			'genre'    => $this->faker->word(),
			'director' => json_encode([
				'en' => $this->faker->name(),
				'ka' => $this->faker->name(),
			]),
		];
	}
}
