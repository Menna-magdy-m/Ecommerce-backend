<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'comment_post_ID'=>'int|required|exists:wp_posts,ID',
            "comment_author" => 'string',
            "comment_author_email" => "string",
            "comment_approved" => "int",
            "user_id" => 'int',
            "comment_content" => "string|required",
            "comment_type" => "string"
        ];
    }
}
