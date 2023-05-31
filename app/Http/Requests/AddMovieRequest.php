<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMovieRequest extends FormRequest
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
	 *
	 * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			//			'title_en'        => 'required|string',
			//			'title_ka'        => 'required|string',
			//			'genre'           => 'required|string',
			//			'director_en'     => 'required|string',
			//			'director_ka'     => 'required|string',
			//			'release_date'    => 'required|date',
			//			'description_en'  => 'required|string',
			//			'description_ka'  => 'required|string',
			'poster'          => 'required|image',
		];
	}

	protected function prepareForValidation(): void
	{
		$this->merge([
			'title'       => json_encode(['en' => $this->title_en, 'ka' => $this->title_ka]),
			'director'    => json_encode(['en' => $this->director_en, 'ka' => $this->director_ka]),
			'description' => json_encode(['en' => $this->description_en, 'ka' => $this->description_ka]),
		]);
	}
}
