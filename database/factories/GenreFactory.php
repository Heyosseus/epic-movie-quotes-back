<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Genre>
 */
class GenreFactory extends Factory
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
				'name' => [
					'en' => 'Action',
					'ka' => 'მოქმედება',
				],
			],
			[
				'name' => [
					'en' => 'Comedy',
					'ka' => 'კომედია',
				],
			],
			[
				'name' => [
					'en' => 'Drama',
					'ka' => 'დრამა',
				],
			],
			[
				'name' => [
					'en' => 'Fantasy',
					'ka' => 'ფანტასტიკა',
				],
			],
			[
				'name' => [
					'en' => 'Horror',
					'ka' => 'საშინელება',
				],
			],
			[
				'name' => [
					'en' => 'Mystery',
					'ka' => 'მისტიკა',
				],
			],
			[
				'name' => [
					'en' => 'Romance',
					'ka' => 'რომანი',
				],
			],
			[
				'name' => [
					'en' => 'Thriller',
					'ka' => 'თრილერი',
				],
			],
			[
				'name' => [
					'en' => 'Western',
					'ka' => 'ვესტერნი',
				],
			],
		];

		$genre = $genres[$this->faker->numberBetween(0, count($genres) - 1)];

		return [
			'name' => json_encode($genre['name']),
		];
	}
}
