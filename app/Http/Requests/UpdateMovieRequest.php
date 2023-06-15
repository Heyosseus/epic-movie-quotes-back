<?php

namespace App\Http\Requests;

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
			'title_en'        => 'string|nullable',
			'title_ka'        => 'string|nullable',
			'genre'           => 'nullable',
			'director_en'     => 'string|nullable',
			'director_ka'     => 'string|nullable',
			'release_date'    => 'integer|nullable',
			'description_en'  => 'string|nullable',
			'description_ka'  => 'string|nullable',
		];
	}
}
