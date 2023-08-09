<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
class ProductOrder extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'order_product';
    public $timestamps = false;
    protected $primaryKey = 'ID';
    public $autoincrement = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ID',
        'order_id',
        'product_id',
        'product_meta_id',
        'customer_id',
        'product_qty',
        'product_net_revenue',
        'product_gross_revenue',
        'coupon_amount',
        'tax_amount',
        'shipping_amount',
        'shipping_tax_amount',
        'product_name'
    ];

    protected $with = array("product");

    public function product()
    {
        return $this->hasOne(Product::class,'ID',"product_id");
    }
}
