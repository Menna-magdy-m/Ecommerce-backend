<?php

namespace App\Services;

use App\Dtos\SearchQuery;
use App\Models\Cart;
use App\Models\CouponMeta;
use App\Models\CouponPost;
use App\Models\Product;
use App\Models\ProductVariation;

class CouponMetaService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = [
        'session_id',
        'session_key',
        'session_value',
        'session_expiry'

    ];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = [
        'session_value',
        'session_expiry'];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = [
        'session_key',];
    /**
     *
     */
    protected CartService $cartService;
    protected CouponMeta $couponMeta;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    protected ProductService $product_service;
    protected array $with = [];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return CouponMeta::query();
    }

    public function coupon_amount()
    {
        return ($this->builder()->where("meta_key", '=', 'coupon_amount')->get()[0]->meta_value);
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        return parent::prepare($operation, $attributes);
    }


    /**
     * add coupon to cart
     */

    public function add(array $attributes)
    {
        $coupon = $this->get_coupon_id($attributes["coupon_name"]);

        if ($coupon instanceof CouponPost) {
            //  dd($attributes["coupon_name"], $this->get_coupon_id($attributes["coupon_name"]));
            $meta = ($coupon->details);
            $this->couponMeta = $meta;

           // dd($this->couponMeta->coupon_amount());
            return $this->ok($this->find($coupon->ID), 'records:get:done');
        } else {
            throw new \Exception('records:find:errors:not_found');
        }

    }


    public function create(array $attributes)
    {
        $attributes["session_expiry"] = 120000000;
        $total = 0;
        $cart = array();
        $attributes["session_key"];
        $session = $this->cartService->get_session_id($attributes["session_key"]);
        if ($session) {
            $id_cart = ($session->session_id);
            return $this->save($id_cart, $attributes);
        }
        foreach ($attributes["session_value"] as $item) {
            $id = $item["product_id"];
            $quantity = $item["quantity"];
            $product = $this->get_product($id);
            $product_meta_id = $item['product_meta_id'];
            $product_meta = $product_meta_id? $product->details()->find($product_meta_id):$product;


            if ($product instanceof Product) {
                $q = $this->check_quantity($id, $quantity);
                if ($q) {
                    $cart[] =
                        ["key" => "3f8b2a81da929223ae025fcec26dde0d",
                            "product_id" => $product->ID,
                            "product_meta_id" => $product_meta_id,
                            "variation_id" => 0,
                            "variation" => [],
                            "quantity" => $quantity,
                            "data_hash" => "b5c1d5ca8bae6d4896cf1807cdf763f0",
                            "line_tax_data" => array(
                                "subtotal" => [],
                                "total" => []),
                            "line_subtotal" => $product_meta->min_price,
                            "line_subtotal_tax" => 0,
                            "line_total" => $product_meta->min_price,
                            "line_tax" => 0];
                    $total += $product_meta * $quantity;
                }
            } else {
                $product = $this->get_variation_product($id);
                if ($product instanceof ProductVariation) {
                    $q = $this->check_quantity_variation($id, $quantity);
                    if ($q) {
                        $cart[] =
                            ["key" => "3f8b2a81da929223ae025fcec26dde0d",
                                "product_id" => $product->post_parent,
                                "variation_id" => $product->ID,
                                "variation" => [],
                                "quantity" => $quantity,
                                "data_hash" => "b5c1d5ca8bae6d4896cf1807cdf763f0",
                                "line_tax_data" => array(
                                    "subtotal" => [],
                                    "total" => []),
                                "line_subtotal" => $product->details[0]->min_price,
                                "line_subtotal_tax" => 0,
                                "line_total" => $product->details[0]->min_price,
                                "line_tax" => 0];
                        $total += $product->details[0]->min_price * $quantity;
                    }
                }
            }
        }
        $session['cart'] = serialize($cart);
        $session['cart_totals'] = serialize($total);

        $session['applied_coupons'] = serialize([]);
        $session['coupon_discount_totals'] = serialize([]);
        $session['coupon_discount_tax_totals'] = serialize([]);
        $session['removed_cart_contents'] = serialize([]);
        $attributes["session_value"] = serialize($session);;
        return $this->ok($this->convert_cart($this->store($attributes)), 'records:create:done');

    }

    public function save($id_cart, array $attributes)
    {

        $attributes["session_expiry"] = 12;
        //$attributes["session_value"] = json_encode($attributes["session_value"], 0, 512);
//dd($attributes["session_value"])
        $total = 0;
        $cart = array();
        foreach ($attributes["session_value"] as $item) {
            $id = $item["product_id"];
            $quantity = $item["quantity"];
            //            dd($item["product_id"]);
            $product = $this->get_product($id);
            $product_meta_id = $item['product_meta_id'];
            $product_meta = $product_meta_id? $product->details()->find($product_meta_id):$product;

            if ($product instanceof Product) {
                $q = $this->check_quantity($id, $quantity);
                if ($q) {
                    $cart[] =
                        ["key" => "3f8b2a81da929223ae025fcec26dde0d",
                            "product_id" => $product->ID,
                            "variation_id" => 0,
                            "product_meta_id" => $product_meta_id,
                            "variation" => [],
                            "quantity" => $quantity,
                            "data_hash" => "b5c1d5ca8bae6d4896cf1807cdf763f0",
                            "line_tax_data" => array(
                                "subtotal" => [],
                                "total" => []),
                            "line_subtotal" => $product->details[0]->min_price,
                            "line_subtotal_tax" => 0,
                            "line_total" => $product->details[0]->min_price,
                            "line_tax" => 0];
                    $total += $product->details[0]->min_price * $quantity;
                }
            } else {
                $product = $this->get_variation_product($id);
                if ($product instanceof ProductVariation) {
                    $q = $this->check_quantity_variation($id, $quantity);
                    if ($q) {
                        $cart[] =
                            ["key" => "3f8b2a81da929223ae025fcec26dde0d",
                                "product_id" => $product->post_parent,
                                "variation_id" => $product->ID,
                                "variation" => [],
                                "quantity" => $quantity,
                                "data_hash" => "b5c1d5ca8bae6d4896cf1807cdf763f0",
                                "line_tax_data" => array(
                                    "subtotal" => [],
                                    "total" => []),
                                "line_subtotal" => $product->details[0]->min_price,
                                "line_subtotal_tax" => 0,
                                "line_total" => $product->details[0]->min_price,
                                "line_tax" => 0];
                        $total += $product->details[0]->min_price * $quantity;
                    }
                }
            }
        }
        $session['cart'] = serialize($cart);
        $session['cart_totals'] = serialize($total);

        $session['applied_coupons'] = serialize([]);
        $session['coupon_discount_totals'] = serialize([]);
        $session['coupon_discount_tax_totals'] = serialize([]);
        $session['removed_cart_contents'] = serialize([]);
        $attributes["session_value"] = serialize($session);
        return $this->ok($this->convert_cart($this->update($id_cart, $attributes)), 'records:save:done');

    }

    public function get($id)
    {
        $session = $this->get_session_id($id);
        if (!$session) {
            $id = 0;
        } else {
            $id = $session->session_id;
        }
        return $this->ok($this->find($id), 'records:get:done');
    }

    public function get_cart($id)
    {
        $data = $this->find($id);
        $d = unserialize($data->session_value);
        $cart = unserialize($d["cart"]);

        $d['cart'] = $cart;
        $total = unserialize($d["cart_totals"]);
        $d['cart_totals'] = $total;
        $d["num_items_sold"] = $this->count_cart($cart);
        $apllaid_coupon = unserialize($d["applied_coupons"]);
        unset($d['applied_coupons']);
        $dicount = unserialize($d["coupon_discount_totals"]);
        unset($d['coupon_discount_totals']);
        $total_disc = unserialize($d["coupon_discount_tax_totals"]);
        unset($d['coupon_discount_tax_totals']);
        $removed_content = unserialize($d["removed_cart_contents"]);
        unset($d['removed_cart_contents']);
        return $d;
    }

    public function count_cart($cart)
    {
        $count = 0;
        foreach ($cart as $item) {
            $count += $item["quantity"];
        }
        return $count;
    }

    public function get_product($id)
    {
        $product_service = new ProductService();
        $product = $product_service->findProduct($id);
        return $product;
    }

    public function check_quantity($id, $quantity)
    {
        $product_service = new ProductService();
        $product = $product_service->find($id);
        if ($product instanceof Product) {
            $q = $product->details()->find($id)->get("stock_quantity", "stock_status");

            $q = ($product->details[0]);
            if ($q->stock_status == "instock") {
                if ($q->stock_quantity != null) {
                    if ($q->stock_quantity >= $quantity) {
                        return true;
                    }
                } else return true;

            }

        }
        return false;

    }

    public function check_quantity_variation($id, $quantity)
    {
        $product_service = new ProductVariationService();
        $product = $product_service->find($id);
        if ($product instanceof ProductVariation) {

            $q = ($product->details[0]);
            if ($q->stock_status == "instock") {
                if ($q->stock_quantity != null) {
                    if ($q->stock_quantity >= $quantity) {
                        return true;
                    }
                } else return true;

            }

        }
        return false;

    }

    private function get_variation_product($id)

    {
        $product_service = new ProductVariationService();
        $product = $product_service->findProduct($id);
        return $product;

    }

    public function empty_cart($id)
    {
        $session['cart'] = serialize([]);
        $session['cart_totals'] = serialize([]);

        $session['applied_coupons'] = serialize([]);
        $session['coupon_discount_totals'] = serialize([]);
        $session['coupon_discount_tax_totals'] = serialize([]);
        $session['removed_cart_contents'] = serialize([]);
        $attributes["session_value"] = serialize($session);
        return parent::save($id, $attributes);

    }

    private function convert_cart(\Illuminate\Database\Eloquent\Model $record): Cart
    {
        $array = array();
        $cart = unserialize($record->session_value);
        foreach ($cart as $item => $value) {
            $array[$item] = unserialize($value);
        }
        $record->session_value = $array;
        return $record;
    }

    private function get_session_id($session_key)
    {
        $result = $this->search(SearchQuery::fromJson(
            ["offset" => "0",
                "limit" => "23",
                "sort" => [
                    "column" => "name",
                    "order" => "asc"],
                "fields" => [
                    "session_key" => [
                        "value" => $session_key
                    ]
                ]
            ]));
        return $result->data[0];
    }

    public function get_coupon_id($code)
    {
        $result = $this->search(SearchQuery::fromJson(
            ["offset" => "0",
                "limit" => "23",
                "sort" => [
                    "column" => "name",
                    "order" => "asc"],
                "fields" => [
                    "post_title" => [
                        "value" => $code
                    ]
                ]
            ]));
        return $result->data[0];

    }

}
