<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class StoreManagerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        $user = $this->user();

        if ($user instanceof User) {
            if ($user->isAdmin()) ;


            return true;
        }
       // if ($user->isAdmin())
            return false;
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
        ];
    }
}
