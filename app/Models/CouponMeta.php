<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class CouponMeta extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_postmeta';
    public $timestamps = false;
    protected $primaryKey = 'meta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //
        'meta_key',
        'meta_value',
        'post_id'
    ];

    protected $with = array();

    public function attribute()
    {
        [
            'code' => '',
            'amount' => 0,
            'status' => null,
            'date_created' => null,
            'date_modified' => null,
            'date_expires' => null,
            'discount_type' => 'fixed_cart',
            'description' => '',
            'usage_count' => 0,
            'individual_use' => false,
            'product_ids' => array(),
            'excluded_product_ids' => array(),
            'usage_limit' => 0,
            'usage_limit_per_user' => 0,
            'limit_usage_to_x_items' => null,
            'free_shipping' => false,
            'product_categories' => array(),
            'excluded_product_categories' => array(),
            'exclude_sale_items' => false,
            'minimum_amount' => '',
            'maximum_amount' => '',
            'email_restrictions' => array(),
            'used_by' => array(),
            'virtual' => false,
        ];
    }

    public function coupon_amount()
    {
        return CouponMeta::query();
    }
}
