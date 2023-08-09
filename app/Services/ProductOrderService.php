<?php

namespace App\Services;

use App\Models\ProductOrder;
use App\Models\User;
use Illuminate\Support\Carbon;

class ProductOrderService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = [

        'ID',
        'order_id',
        'product_id',
        'product_meta_id',
        'customer_id',
        'date_created',
        'product_qty',
        'product_net_revenue',
        'product_gross_revenue',
        'coupon_amount', '
        tax_amount',
        'shipping_amount',
        'shipping_tax_amount'
    ];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = [];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = [];
    /**
     *
     */
    protected array $with = [];

    protected ProductService $productService;
    protected ProductVariationService $productVariationService;

    public function __construct(ProductService $productService, ProductVariationService $productVariationService)
    {
        $this->productService = $productService;
        $this->productVariationService = $productVariationService;

    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return ProductOrder::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {

        return parent::prepare($operation, $attributes);
    }

    public function create(array $attributes)
    {
        $user = auth()->user();
        $rows = array();
        if ($user instanceof User) {
            $customer_id = $user->customer_id();
        }
        foreach ($attributes["cart"] as $product) {
            echo"productOrder";
            $product_item = $this->productService->find($product["product_id"]);

            $amount=$product["line_subtotal"]- $product["line_total"];
            $rows = [
                "product_id" => $product["product_id"],
                "order_id" => $attributes["id"],
                "product_meta_id" => $product["product_meta_id"],
                "customer_id" => $customer_id,
                "product_qty" => $product["quantity"],
                "product_net_revenue" => $product["line_subtotal"] * $product["quantity"],
                "product_gross_revenue" => $product["line_total"] * $product["quantity"],
                "coupon_amount" =>$amount ,
                "tax_amount" => 0,
                "shipping_amount" => 0,
                "shipping_tax_amount" => 0,
                "product_name" => $product_item->title,
            ];
            $recorders = $this->store($rows);
        }

        return $recorders;
    }

    public function destroy($id)
    {
        $record = $this->find($id);
        if ($record instanceof User) {
            $addresses = $record->addresses()->get()->all();
            foreach ($addresses as $address) {
                if ($address instanceof \App\Models\Address) {
                    $address->delete();
                }
            }
        }
        return parent::destroy($id);
    }
}
