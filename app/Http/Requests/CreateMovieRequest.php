<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateMovieRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title_en' => 'required',
            'title_ka' => 'required',
            'genre' => 'required',
            'date' => 'required',
            'director_en' => 'required',
            'director_ka' => 'required',
            'description_en' => 'required',
            'description_ka' => 'required',
            'img' => 'required|image',
            'user_id' => 'required',
        ];
    }





}
