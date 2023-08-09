<?php

namespace App\Services;

use App\Dtos\SearchQuery;
use App\Models\Cart;
use App\Models\CouponPost;

class CouponService extends ModelService
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
    protected CouponMetaService $couponMetaService;

    public function __construct(CartService $cartService, CouponMetaService $couponMetaService)
    {
        $this->cartService = $cartService;
        $this->couponMetaService = $couponMetaService;
    }

    protected ProductService $product_service;
    protected array $with = [];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return CouponPost::query();
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
            $amount = $this->get_amount($coupon);
            $session = $this->cartService->get_session_id($attributes["session_key"]);
            $session_id = $session["session_id"];
            if ($session instanceof Cart) {
                $session = $this->cartService->convert_cart($session);
                $subtotal = 0;
                $session = $session["session_value"];
                $discount_amount = 0;
                $products = array();
                $discount_total = $session["cart_totals"]['discount_total'];
                if (in_array($coupon->post_title, $session["applied_coupons"])) {

                    return $this->ok(["coupon already added"], 'records:get:done');
                }
                $cart_contents_total = 0;
                foreach ($session["cart"] as $index => $product) {
                    $discount = $amount * $product["quantity"];
                    $product["line_total"] = $product["line_total"] - $discount;
                    $subtotal += $product["line_subtotal"];
                    $discount_amount += $discount;
                    $cart_contents_total += $product["line_total"] - $discount;
                    $discount_total += $discount;

                    $products[] = $product;
                }
                $new_cart = $products;
                $new_session["cart"] = $new_cart;
                $new_session["applied_coupons"] = $session["applied_coupons"];
                $new_session['applied_coupons'][] = $attributes["coupon_name"];

                $new_session["cart_totals"] = array(
                    "subtotal" => $subtotal,
                    "subtotal_tax" => 0,
                    "shipping_total" => "0",
                    "shipping_tax" => 0,
                    "shipping_taxes" => [],
                    "discount_total" => $discount_total,
                    "discount_tax" => 0,
                    "cart_contents_total" => $cart_contents_total,
                    "cart_contents_tax" => 0,
                    "cart_contents_taxes" => [],
                    "fee_total" => "0",
                    "fee_tax" => 0,
                    "fee_taxes" => [],
                    "total" => $cart_contents_total,
                    "total_tax" => 0.0
                );

                $new_session['coupon_discount_totals'] = [$attributes["coupon_name"] => $discount_amount];
                $new_session['coupon_discount_tax_totals'] = [];
                $new_session['removed_cart_contents'] = [];

                $session["session_key"] = $attributes["session_key"];
                $session["session_value"] = $this->cartService->serialize($new_session);
                ($this->cartService->update($session_id, $session));

                return $this->ok($this->cartService->convert_cart($this->cartService->get_session_id($attributes["session_key"])), 'records:get:done');
            } else {
                throw new \Exception('records:find:errors:cart_empty');

            }
        }

    }

    public function get($id)
    {
        return $this->ok($this->find($id), 'records:get:done');
    }

    public function get_coupon_id($code)
    {
        $result = $this->search(SearchQuery::fromJson(
            ["offset" => "0",
                "limit" => "23",
                "sort" => [
                    "column" => "post_title",
                    "order" => "asc"],
                "fields" => [
                    "post_title" => [
                        "value" => $code
                    ]
                ]
            ]));
        if ($result->data[0])
            return $result->data[0];
        else {
            throw new \Exception('records:find:errors:not_found');

        }
    }

    public function remove(array $attributes)
    {
        $this->get_coupon_id($attributes["coupon_name"]);
    }

    public function get_amount($coupon)
    {
        return $coupon->meta()->where("meta_key", "=", "coupon_amount")->get()->first()->meta_value;
    }

    public function get_usage_count($coupon)
    {
        return $coupon->meta()->where("meta_key", "=", "usage_count")->get()->first();
    }

    public function get_usage_limit($coupon)
    {
        return $coupon->meta()->where("meta_key", "=", "usage_limit")->get()->first();
    }

    public function check_usage_limit_if_it_is_zero($coupon)
    {
        $limit = $coupon->meta()->where("meta_key", "=", "usage_limit")->get()->first()->meta_value;
        if ($limit <= 0) {
            return false;
        }
    }


}
