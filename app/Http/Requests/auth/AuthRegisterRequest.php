<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 */
	public function rules(): array
	{
		return [
			'name'                  => 'required|unique:users|min:3|max:15',
			'email'                 => 'required|string|email|unique:users',
			'password'              => 'required|string|min:8|max:15|confirmed',
			'password_confirmation' => 'required|same:password',
		];
	}
}
