<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttachmentRequest extends FormRequest
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
             'file' => 'max:20000|mimes:pdf,jpg,jpeg,png,bmp,svg,tiff,mp4,mov,ogg,qt',
             'title' =>'required|string'

        ];
    }
}
