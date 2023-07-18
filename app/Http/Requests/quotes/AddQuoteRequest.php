<?php

namespace App\Http\Requests\quotes;

use Illuminate\Foundation\Http\FormRequest;

class AddQuoteRequest extends FormRequest
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
			'body_en'   => 'required',
			'body_ka'   => 'required',
			'thumbnail' => 'required',
			'movie_id'  => 'required',
			'user_id'   => 'required',
		];
	}

	protected function prepareForValidation(): void
	{
		$this->merge([
			'body'       => json_encode(['en' => $this->body_en, 'ka' => $this->body_ka]),
		]);
	}
}
