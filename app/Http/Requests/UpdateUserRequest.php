<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'name'                   => 'unique:users,name',
			'password'               => 'string',
			'password_confirmation'  => 'string',
			'email'                  => 'unique:users,email',
			'img'                    => 'image',
		];
	}
}
