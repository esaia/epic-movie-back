<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateQuoteRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'quote_en' => 'string',
            'quote_ka' => 'string',
            'img' => 'image',
        ];
    }
}
