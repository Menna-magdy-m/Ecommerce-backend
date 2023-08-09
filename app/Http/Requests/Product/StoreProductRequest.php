<?php


namespace App\Http\Requests\Product;


use App\Models\ProductStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreProductRequest extends FormRequest
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
            "content" => "bail|max:255|string",
            "title" => "string|required|unique:products",
            "status" => "required|",
            "comment_status" => "in:open,close",
            "sku" => "string|alpha_num:ascii",
            "virtual" => new Enum(ProductStatus::class),
            "downloadable" => 'boolean',
            "max_price" => "numeric",
            "min_price" => "numeric|lte:max_price",
            "stock_quantity" => 'int',
            "stock_status" => "in:instock,outstock",
            "category.*" => "exists:categories,ID"

        ];
    }
}
