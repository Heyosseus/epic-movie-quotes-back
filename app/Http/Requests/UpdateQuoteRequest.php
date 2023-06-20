<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function authorize(): bool
	{
		return true;
	}

	public function rules(): array
	{
		return [
			'body_en'   => 'required',
			'body_ka'   => 'required',
			'thumbnail' => 'required',
			'movie_id'  => 'required',
		];
	}

	protected function prepareForValidation(): void
	{
		$this->merge([
			'body'       => json_encode(['en' => $this->body_en, 'ka' => $this->body_ka]),
		]);
	}
}
