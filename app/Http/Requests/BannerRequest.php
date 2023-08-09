<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class BannerRequest extends FormRequest
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
             'post_title' =>Rule::in([
                'l_s_1',
                'l_s_2',
                'l_s_3',
                'l_s_4',
                'l_s_5',
                'l_s_6',
                'l_s_7',
                'category_s_1',
                'cart_s_1'])
        ];
    }
}
