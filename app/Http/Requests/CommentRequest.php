<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
         'comment' => 'required',
         'quote_id'  => 'required',
         'user_id' => 'required'
        ];
    }
}
