<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Container\Container;
use Illuminate\Foundation\Http\FormRequest;

class UpdateManagerRequest extends FormRequest
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
        $id = $this->route('id');
        $service = Container::getInstance()->get(UserService::class);
        $editor = $service->find($id);
        if ($editor instanceof User) {
            if ($user->isAdmin()) ;

            if ($editor->ID == $user->ID) {
                return true;
            }
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
            'user_login' => 'string|unique:wp_users,user_login',
            'user_nicename' => 'string',
            'user_email' => 'string|unique:wp_users,user_email|email',
            'user_pass' => 'string',
            'enable' => 'boolean',
        ];
    }
}
