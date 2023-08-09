<?php

namespace App\Services;

use App\Models\ProductOrderItem;
use App\Models\User;

class ProductOrderItemService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = [
        'order_item_id',
        'order_item_name',
        'order_item_type',
        'order_id',
    ];
    protected ProductService $productService;
    protected ProductVariationService $productVariationService;
    protected ProductOrderService $productOrderService;

    public function __construct(ProductService $productService, ProductVariationService $productVariationService, ProductOrderService $productOrderService)
    {
        $this->productService = $productService;
        $this->productVariationService = $productVariationService;
        $this->productOrderService = $productOrderService;

    }

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

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return ProductOrderItem::query();
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
        foreach ($attributes["cart"] as $product) {

            if ($product["variation_id"]) {

                $product_item = $this->productVariationService->find($product["variation_id"]);
            } else {
                $product_item = $this->productService->find($product["product_id"]);

            }
            $rows = [
                'order_item_name' => $product_item->title,
                'order_item_type' => "line_item",
                "order_id" => $attributes["id"],

            ];
            $product["row"] = $rows;
            $product["product"] = $product_item;
            $record = $this->store($product);
            $product["order_item_id"] = $record->order_item_id;
            $product["order_id"] = $attributes["id"];
            $this->productOrderService->create($product);

        }

        return $record;
    }

    public function store(array $attributes): ProductOrderItem
    {
        $record = parent::store($attributes["row"]);
        // TODO: sites attribute value
        if ($record instanceof ProductOrderItem) {
            $record->meta()->create(["meta_key" => "_product_id", "meta_value" => $attributes["product_id"]]);
            $record->meta()->create(["meta_key" => "_product_meta_id", "meta_value" => $attributes["product_meta_id"]]);
            $record->meta()->create(["meta_key" => "_qty", "meta_value" => $attributes["quantity"]]);
            $record->meta()->create(["meta_key" => "_tax_class", "meta_value" => ""]);
            $record->meta()->create(["meta_key" => "_line_subtotal", "meta_value" => $attributes["line_total"] * $attributes["quantity"]]);
            $record->meta()->create(["meta_key" => "_line_subtotal_tax", "meta_value" => ""]);
            $record->meta()->create(["meta_key" => "_line_total", "meta_value" => $attributes["line_total"] * $attributes["quantity"]]);
            $record->meta()->create(["meta_key" => "_line_tax", "meta_value" => ""]);
            $record->meta()->create(["meta_key" => "_line_tax_data", "meta_value" => ""]);
            $record->meta()->create(["meta_key" => "_reduced_stock", "meta_value" => "1"]);
            if ($attributes["variation_id"]) {
                foreach ($attributes["product"]["attribute"] as $product_attribute) {
                    $record->meta()->create(["meta_key" => $product_attribute->taxonomy, "meta_value" => $product_attribute->attribute_name()->get()->toArray()[0]["name"]]);
                }
            }
        }
        return $record;
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
