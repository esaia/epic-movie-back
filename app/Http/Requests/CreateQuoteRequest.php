<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quote_en' => 'required',
            'quote_ka' => 'required',
            'img' => 'required|image',
            'movie_id' => 'required',
        ];
    }
}
