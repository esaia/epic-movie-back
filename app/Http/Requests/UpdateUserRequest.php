<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
             'name' => 'string',
             'password' => 'string',
             'password_confirmation' => 'string',
             'img' => 'image',
        ];
    }
}
