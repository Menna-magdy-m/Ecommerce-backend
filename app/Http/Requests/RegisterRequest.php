<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_login' => 'required|string|unique:wp_users,user_login',
            'user_nicename' => 'string',
            'user_email' => 'required|string|unique:wp_users,user_email|email',
            'user_pass' => 'required|string',
            'enable' => 'boolean',
            'categories' => 'array',
            'categories.*' => 'required|int|exists:categories,id',
        ];
    }
}
