<?php

namespace App\Http\Requests\Movies;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 */
	public function rules(): array
	{
		return [
			'title_en'        => 'string',
			'title_ka'        => 'string',
			'director_en'     => 'string',
			'director_ka'     => 'string',
			'release_date'    => 'integer',
			'description_en'  => 'string',
			'description_ka'  => 'string',
			'poster'          => '',
		];
	}

	protected function prepareForValidation(): void
	{
		$this->merge([
			'title'       => ['en' => $this->title_en, 'ka' => $this->title_ka],
			'director'    => json_encode(['en' => $this->director_en, 'ka' => $this->director_ka]),
			'description' => json_encode(['en' => $this->description_en, 'ka' => $this->description_ka]),
		]);
	}
}
