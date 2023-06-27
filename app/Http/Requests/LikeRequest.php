<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LikeRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'user_id'  => 'required',
			'quote_id' => 'required',
		];
	}
}
