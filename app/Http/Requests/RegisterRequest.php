<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:15|unique:users,name',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:8|max:15|confirmed',
        ];
    }
}
