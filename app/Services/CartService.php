<?php

namespace App\Services;

use App\Dtos\SearchQuery;
use App\Models\Cart;
use App\Models\Product;
use App\Models\ProductVariation;

class CartService extends ModelService
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
    protected ProductService $product_service;
    protected array $with = [];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Cart::query();
    }


    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        return parent::prepare($operation, $attributes);
    }


    /**
     * create a new user
     */
    public function create(array $attributes)
    {
        $attributes["session_expiry"] = 120000000;
        $total = 0;
        $cart = array();
        $session = $this->get_session_id($attributes["session_key"]);
        if ($session) {
            $id_cart = ($session->session_id);
            return $this->save($id_cart, $attributes);
        }
        foreach ($attributes["session_value"] as $item) {
            $id = $item["product_id"];
            $product_meta_id = $item["product_meta_id"];
            $quantity = $item["quantity"];
            $product = $this->get_product($id);
            $meta = null;
            if ($product instanceof Product) {
                if($product_meta_id && $product->details()->find($product_meta_id)){
                    $meta = $product->details()->find($product_meta_id);
                }
                $q = $this->check_quantity($id, $quantity,$meta);
                if ($q) {   //enough quantity => q=true
                    $sub_total = $meta? $meta->min_price * $quantity :$product->min_price * $quantity;

                    $cart[] =
                        ["key" => "3f8b2a81da929223ae025fcec26dde0d",
                            "product_id" => $product->ID,
                            "product_meta_id" => $item["product_meta_id"],
                            "variation_id" => 0,
                            "variation" => [],
                            "quantity" => $quantity,
                            "data_hash" => "b5c1d5ca8bae6d4896cf1807cdf763f0",
                            "line_tax_data" => array(
                                "subtotal" => [],
                                "total" => []),
                            "line_subtotal" => $sub_total,
                            "line_subtotal_tax" => 0,
                            "line_total" => $sub_total,
                            "line_tax" => 0];
                    $total += $sub_total;
                }
            } else {
                $product = $this->get_variation_product($id);
                if ($product instanceof ProductVariation) {
                    $q = $this->check_quantity_variation($id, $quantity);
                    if ($q) {
                        $sub_total = $product->details[0]->min_price * $quantity;
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
                                "line_subtotal" => $sub_total,
                                "line_subtotal_tax" => 0,
                                "line_total" => $sub_total,
                                "line_tax" => 0];
                        $total += $product->details[0]->min_price * $quantity;
                    }
                }
            }
        }
        $session['cart'] = $cart;
        $session['cart_totals'] = array(
            "subtotal" => $total,
            "subtotal_tax" => 0,
            "shipping_total" => "0",
            "shipping_tax" => 0,
            "shipping_taxes" => [],
            "discount_total" => 0.0,
            "discount_tax" => 0,
            "cart_contents_total" => $total,
            "cart_contents_tax" => 0,
            "cart_contents_taxes" => [],
            "fee_total" => "0",
            "fee_tax" => 0,
            "fee_taxes" => [],
            "total" => $total,
            "total_tax" => 0.0
        );

        $session['applied_coupons'] = [];
        $session['coupon_discount_totals'] = [];
        $session['coupon_discount_tax_totals'] = [];
        $session['removed_cart_contents'] = [];

        $attributes["session_value"] = $this->serialize($session);
        return $this->ok($this->convert_cart($this->store($attributes)), 'records:create:done');

    }

    public function save($id_cart, array $attributes)
    {
        $attributes["session_expiry"] = 12;
        $total = 0;
        $cart = array();
        foreach ($attributes["session_value"] as $item) {
            $id = $item["product_id"];
            $product_meta_id = $item["product_meta_id"];
            $quantity = $item["quantity"];
            //            dd($item["product_id"]);

            $product = $this->get_product($id);
            $meta = null;
            if ($product instanceof Product) {
                if($product_meta_id && $product->details()->find($product_meta_id)){
                    $meta = $product->details()->find($product_meta_id);
                }
                $q = $this->check_quantity($id, $quantity,$meta);

                if ($q) {
                    $sub_total = $meta? $meta->min_price * $quantity :$product->min_price * $quantity;


                    $cart[] =
                        ["key" => "3f8b2a81da929223ae025fcec26dde0d",
                            "product_id" => $product->ID,
                            "product_meta_id" => $item["product_meta_id"],
                            "variation_id" => 0,
                            "variation" => [],
                            "quantity" => $quantity,
                            "data_hash" => "b5c1d5ca8bae6d4896cf1807cdf763f0",
                            "line_tax_data" => array(
                                "subtotal" => [],
                                "total" => []),
                            "line_subtotal" => $sub_total,
                            "line_subtotal_tax" => 0,
                            "line_total" => $sub_total,
                            "line_tax" => 0];
                    $total += $sub_total;
                }
            } else {
                $product = $this->get_variation_product($id);
                if ($product instanceof ProductVariation) {
                    $q = $this->check_quantity_variation($id, $quantity);
                    if ($q) {
                        $sub_total = $product->details[0]->min_price * $quantity;

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
                                "line_subtotal" => $sub_total,
                                "line_subtotal_tax" => 0,
                                "line_total" => $sub_total,
                                "line_tax" => 0];
                        $total += $product->details[0]->min_price * $quantity;
                    }
                }
            }
        }
        $session['cart'] = serialize($cart);
        $session['cart_totals'] = serialize(array(
            "subtotal" => $total,
            "subtotal_tax" => 0,
            "shipping_total" => "0",
            "shipping_tax" => 0,
            "shipping_taxes" => [],
            "discount_total" => 0.0,
            "discount_tax" => 0,
            "cart_contents_total" => $total,
            "cart_contents_tax" => 0,
            "cart_contents_taxes" => [],
            "fee_total" => "0",
            "fee_tax" => 0,
            "fee_taxes" => [],
            "total" => $total,
            "total_tax" => 0.0
        ));

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
        return $this->ok($this->convert_cart($this->find($id)), 'records:get:done');
    }

    public function get_cart($id)
    {
        $session = $this->get_session_id($id);
        return $this->unserialize($session->session_value);
    }

    public function is_empty($cart_id)
    {
        $session = $this->get_cart($cart_id);
        return $this->count_cart($session["cart"]);
    }

    public function count_cart($cart)
    {
        $count = 0;
        if (is_array($cart))
            foreach ($cart as $item) {
                $count += array_key_exists("quantity",$item)?$item["quantity"]:0;
            }
        return $count;
    }

    public function get_product($id)
    {
        $product_service = new ProductService();
        $product = $product_service->findProduct($id);
        return $product;
    }

    public function check_quantity($id, $quantity,$meta=null)
    {

        $product_service = new ProductService();
        $product = $product_service->find($id);
        if ($product instanceof Product) {

            if($meta){
                $q = $meta;
            }else{
                $q = $product;
            }

            //dd($q);
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
        $session['cart_totals'] = serialize(array(
            "subtotal" => 0,
            "subtotal_tax" => 0,
            "shipping_total" => "0",
            "shipping_tax" => 0,
            "shipping_taxes" => [],
            "discount_total" => 0.0,
            "discount_tax" => 0,
            "cart_contents_total" => 0,
            "cart_contents_tax" => 0,
            "cart_contents_taxes" => [],
            "fee_total" => "0",
            "fee_tax" => 0,
            "fee_taxes" => [],
            "total" => 0,
            "total_tax" => 0.0
        ));

        $session['applied_coupons'] = serialize([]);
        $session['coupon_discount_totals'] = serialize([]);
        $session['coupon_discount_tax_totals'] = serialize([]);
        $session['removed_cart_contents'] = serialize([]);
        $attributes["session_value"] = serialize($session);
        $session = $this->get_session_id($id);
        return parent::save($session->session_id, $attributes);

    }

    public function convert_cart(\Illuminate\Database\Eloquent\Model $record): Cart
    {
        $array = array();
        $cart = unserialize($record->session_value);
        foreach ($cart as $item => $value) {
            $array[$item] = unserialize($value);
        }
        $record->session_value = $array;
        return $record;
    }

    public function get_session_id($session_key)
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

    public function serialize($session)
    {
        $s_session['cart'] = serialize($session['cart']);
        $s_session['cart_totals'] = serialize($session['cart_totals']);
        $s_session['applied_coupons'] = serialize($session['applied_coupons']);
        $s_session['coupon_discount_totals'] = serialize($session['coupon_discount_totals']);
        $s_session['coupon_discount_tax_totals'] = serialize($session['coupon_discount_tax_totals']);
        $s_session['removed_cart_contents'] = serialize($session['removed_cart_contents']);

        return serialize($s_session);
    }

    public function unserialize($session)
    {
        $session = unserialize($session);
        $s_session['cart'] = unserialize($session['cart']);
        $s_session['cart_totals'] = unserialize($session['cart_totals']);
        $s_session['applied_coupons'] = unserialize($session['applied_coupons']);
        $s_session['coupon_discount_totals'] = unserialize($session['coupon_discount_totals']);
        $s_session['coupon_discount_tax_totals'] = unserialize($session['coupon_discount_tax_totals']);
        $s_session['removed_cart_contents'] = unserialize($session['removed_cart_contents']);
        return $s_session;
    }


}
