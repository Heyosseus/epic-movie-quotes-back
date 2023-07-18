<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthLoginRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */

	/**
	 * Get the validation rules that apply to the request.
	 *
//     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
	 */
	public function rules(): array
	{
		return [
			'email'       => 'required_without_all:name,email|email|nullable',
			'name'        => 'nullable|required_without_all:email,name|string|nullable',
			'password'    => 'required',
			'remember_me' => 'boolean',
		];
	}
}
