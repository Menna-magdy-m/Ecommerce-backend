<?php

namespace App\Services;

use App\Models\CouponMeta;
use App\Models\CouponPost;
use App\Models\OrderCoupon;
use App\Models\OrderMeta;
use App\Models\OrderPost;
use App\Models\post_meta;
use App\Models\PostMeta;
use App\Models\Product;
use Illuminate\Support\Carbon;

class OrderPostService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = [
        'post_title',
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content',
        'post_title',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count'
    ];

    protected ProductOrderService $productOrder;
    protected OrderService $order;
    protected OrderMeta $orderMeta;
    protected CartService $cartService;
    protected CouponService $couponService;
    protected CouponMetaService $couponMetaService;
    protected array $updatables = [
    ];
    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = [];
    /**
     *
     */
    protected array $with = ['details'];

    public function __construct(ProductOrderService $productOrder, OrderService $order, OrderMeta $orderMeta, CartService $cartService, CouponService $couponService, CouponMetaService $couponMetaService)
    {
        $this->order = $order;
        $this->productOrder = $productOrder;
        $this->orderMeta = $orderMeta;
        $this->cartService = $cartService;
        $this->couponService = $couponService;
        $this->couponMetaService = $couponMetaService;
    }

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return OrderPost::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        return parent::prepare($operation, $attributes);
    }


    /**
     * create a new address
     */
    public function create(array $attributes)
    {
        $cart_id = $attributes["cart_id"];
        if (!$this->is_empty($cart_id)) {
            throw new \Exception('records:store:errors:empty_cart');
        }

        $cart = $this->get_cart($cart_id);

        foreach ($cart["coupon_discount_totals"] as $code => $discount) {
            $coupon = $this->couponService->get_coupon_id($code);
            $usage_limit = $this->couponService->get_usage_limit($coupon);

            if ($coupon instanceof CouponPost) {
                if ($usage_limit->meta_value == 0) {
                    throw new \Exception("no:more:coupon:available");
                } else {
                    $this->update($cart_id, $cart);
                }
            }
        }
        $attributes["post_title"] = 'Order';
        $attributes["post_author"] = auth()->user()->ID;
        $attributes["post_date"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
        $attributes["post_date_gmt"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
        $attributes["post_content"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
        $attributes["post_excerpt"] = "";
        $attributes["post_status"] = "wc-processing";
        $attributes["comment_status"] = "open";
        $attributes["ping_status"] = "closed";
        $attributes["post_password"] = "wc_order_BMSOR1NLUNhhs";
        $attributes["post_name"] = "Order-" . Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');;
        $attributes["to_ping"] = "";;
        $attributes["pinged"] = "";;
        $attributes["post_modified"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');;
        $attributes["post_modified_gmt"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');;
        $attributes["post_content_filtered"] = "";;
        $attributes["post_parent"] = "0";;
        $attributes["guid"] = "0";;
        $attributes["menu_order"] = "0";;
        $attributes["post_type"] = "shop_order";;
        $attributes["post_mime_type"] = "";;
        $attributes["comment_count"] = "0";
        $data = $this->store($attributes);

        // decrease quantity of products
        $result = $this->get($data->ID);

        foreach ($result->data['products'] as $item) {
            $id = $item->product_id;
            $quantity = $item->product_qty;

            $this->decrese_quantity($id, $quantity);
        }


        return $this->ok($result->data, 'order:save:done');
//        return $this->get($data->ID);
//        return $this->ok($data, 'order:save:done');
    }


    /**
     * Decrease product quantity
     */
    public function decrese_quantity($id, $quantity)
    {
        $product_service = new ProductService();
        $product = $product_service->find($id);

        if ($product instanceof Product) {

            $product->stock_quantity = $product->stock_quantity - $quantity;

            $newRecord = $product->save();
        }
    }


    /**
     * create a new Order
     * @throws \Exception
     */
    public function store(array $attributes): OrderPost
    {
        $record = parent::store($attributes);
        // TODO: sites attribute value
        if ($record instanceof OrderPost) {
            $attributes["order_id"] = $record->ID;
            $order_main = $record;
            $cart = $this->get_cart($attributes["cart_id"]);
            $record->order_meta()->create(
                ["meta_key" => '_order_key', 'meta_value' => "wc_order_lDu8Li0eRJccm"]);
            $record->order_meta()->create(
                ["meta_key" => '_customer_user', 'meta_value' => auth()->user()->ID]);
            $record->order_meta()->create(
                ["meta_key" => '_payment_method', 'meta_value' => $attributes["payment_method"]]);
            $record->order_meta()->create(
                ["meta_key" => '_payment_method_title', 'meta_value' => "payment_method_title"]);
            $record->order_meta()->create(
                ["meta_key" => '_customer_ip_address', 'meta_value' => "1::"]);
            $record->order_meta()->create(
                ["meta_key" => '_customer_user_agent', 'meta_value' => "mobile"]);
            $record->order_meta()->create(
                ["meta_key" => '_created_via', 'meta_value' => "mobile"]);
            $record->order_meta()->create(
                ["meta_key" => '_cart_hash', 'meta_value' => "sdfsdfsfd"]);
            $record->order_meta()->create(
                ["meta_key" => '_download_permissions_granted', 'meta_value' => "yes"]);
            $record->order_meta()->create(
                ["meta_key" => '_recorded_sales', 'meta_value' => "yes"]);
            $record->order_meta()->create(
                ["meta_key" => '_recorded_coupon_usage_counts', 'meta_value' => "yes"]);
            $record->order_meta()->create(
                ["meta_key" => '_new_order_email_sent', 'meta_value' => "true"]);
            $record->order_meta()->create(
                ["meta_key" => '_order_stock_reduced', 'meta_value' => "yes"]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_first_name', 'meta_value' => "test"]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_last_name', 'meta_value' => "test"]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_company', 'meta_value' => "test"]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_address_1', 'meta_value' => "test"]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_address_2', 'meta_value' => "test"]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_city', 'meta_value' => "test"]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_state', 'meta_value' => "test"]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_country', 'meta_value' => "test"]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_email', 'meta_value' => "test@test.test"]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_phone', 'meta_value' => "032165489"]);
            $record->order_meta()->create(
                ["meta_key" => '_order_currency', 'meta_value' => "AED"]);
            $record->order_meta()->create(
                ["meta_key" => '_cart_discount', 'meta_value' => "0"]);
            $record->order_meta()->create(
                ["meta_key" => '_cart_discount_tax', 'meta_value' => "0"]);
            $record->order_meta()->create(
                ["meta_key" => '_order_shipping', 'meta_value' => "0"]);
            $record->order_meta()->create(
                ["meta_key" => '_order_shipping_tax', 'meta_value' => "0"]);
            $record->order_meta()->create(
                ["meta_key" => '_order_tax', 'meta_value' => "0"]);
            $record->order_meta()->create(
                ["meta_key" => '_order_total', 'meta_value' => $cart["cart_totals"]["total"]]);
            $record->order_meta()->create(
                ["meta_key" => '_order_version', 'meta_value' => "7.4.0"]);
            $record->order_meta()->create(
                ["meta_key" => '_prices_include_tax', 'meta_value' => "no"]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_address_index', 'meta_value' => "test"]);
            $record->order_meta()->create(
                ["meta_key" => '_shipping_address_index', 'meta_value' => ""]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_dokan_company_id_number', 'meta_value' => ""]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_dokan_vat_number', 'meta_value' => ""]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_dokan_bank_name', 'meta_value' => ""]);
            $record->order_meta()->create(
                ["meta_key" => '_billing_dokan_bank_iban', 'meta_value' => ""]);
            $record->order_meta()->create(
                ["meta_key" => 'is_vat_exempt', 'meta_value' => "no"]);
            $record->order_meta()->create(
                ["meta_key" => '_dokan_vendor_id', 'meta_value' => 2]);
            $record->order_meta()->create(
                ["meta_key" => 'shipping_fee_recipient', 'meta_value' => "seller"]);
            $record->order_meta()->create(
                ["meta_key" => 'tax_fee_recipient', 'meta_value' => "seller"]);
            $record->order_meta()->create(
                ["meta_key" => '_edit_lock', 'meta_value' => "1685519253:2"]
            );

            $this->order->create($attributes);

            foreach ($cart["coupon_discount_totals"] as $code => $discount) {
                $coupon = $this->couponService->get_coupon_id($code);

                $usage_count = $this->couponService->get_usage_count($coupon);
                $usage_limit = $this->couponService->get_usage_limit($coupon);
                $amount = ($this->couponService->get_amount($coupon));

                if ($coupon instanceof CouponPost) {
                    if ($usage_count instanceof post_meta && $usage_limit instanceof post_meta) {
                        // 1- update usage_count attribute
                        $usage_count->update([
                            'meta_value' => $usage_count->meta_value + 1
                        ]);
                        // 2- update usage_limit attribute
                        $usage_limit->update([
                            'meta_value' => $usage_limit->meta_value - 1
                        ]);
                    }
                }

                $d[] = OrderCoupon::query()->create(["coupon_id" => $coupon->ID, "order_id" => $attributes["order_id"], "date_created" => Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s')
                    , "discount_amount" => $amount]);
            }
        }
        return $record;
    }


    public function save($comment_ID, array $attributes)
    {
        return parent::save($comment_ID, $attributes);
    }

    public function get_cart($cart_id)
    {
        $cart = $this->cartService->get_cart($cart_id);
        return $cart;
    }

    public function empty_cart($cart_id)
    {
        $this->cartService->empty_cart($cart_id);
        return true;
    }

    private function is_empty($cart_id)
    {
        return $this->cartService->is_empty($cart_id);
    }


}
