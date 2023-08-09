<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class ProductOrderItem extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_woocommerce_order_items';
    public $timestamps = false;
    protected $primaryKey = 'order_item_id';
    public $autoincrement = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_item_id',
        'order_item_name',
        'order_item_type',
        'order_id',

    ];

    protected $with = array();

    public function meta()
    {
        return $this->hasMany(OrderItemMeta::class, "order_item_id", "order_item_id");
    }

    public function product()
    {
        return $this->hasMany(ProductOrder::class, "order_item_id", "order_item_id");
    }
}
