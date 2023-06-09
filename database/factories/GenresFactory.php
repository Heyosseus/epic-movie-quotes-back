<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Genres>
 */
class GenresFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition(): array
	{
		$genres = [
			[
				'name' => json_encode([
					'en' => 'Action',
					'ka' => 'მოქმედება',
				]),
			],
			[
				'name' => json_encode([
					'en' => 'Comedy',
					'ka' => 'კომედია',
				]),
			],
			[
				'name' => json_encode([
					'en' => 'Drama',
					'ka' => 'დრამა',
				]),
			],
			[
				'name' => json_encode([
					'en' => 'Fantasy',
					'ka' => 'ფანტასტიკა',
				]),
			],
			[
				'name' => json_encode([
					'en' => 'Horror',
					'ka' => 'საშინელება',
				]),
			],
			[
				'name' => json_encode([
					'en' => 'Mystery',
					'ka' => 'მისტიკა',
				]),
			],
			[
				'name' => json_encode([
					'en' => 'Romance',
					'ka' => 'რომანი',
				]),
			],
			[
				'name' => json_encode([
					'en' => 'Thriller',
					'ka' => 'თრილერი',
				]),
			],
			[
				'name' => json_encode([
					'en' => 'Western',
					'ka' => 'ვესტერნი',
				]),
			],
		];
		return $genres[$this->faker->numberBetween(0, count($genres) - 1)];
	}
}
