<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
{
	public function rules(): array
	{
		return [
			'title_en'       => 'required|string',
			'title_ka'       => 'required|string',
			'genre'          => 'required',
			'date'           => 'required|string',
			'director_en'    => 'required|string',
			'director_ka'    => 'required|string',
			'description_en' => 'required|string',
			'description_ka' => 'required|string',
			'img'            => 'image',
			'user_id'        => 'string',
		];
	}
}
