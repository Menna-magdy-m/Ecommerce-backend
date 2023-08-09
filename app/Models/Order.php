<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Order extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'order';
    public $timestamps = true;
    protected $primaryKey = 'ID';


    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {

        static::addGlobalScope('accessDB', function (Builder $builder) {
            $user = auth()->user();
            if ($user instanceof User) {
                if($user->isAdmin()){

                    // $builder->where(1);
                }else{
                    $builder->where("customer_id", "=", $user->ID);
                }



            } else {
                //    $builder->where('user_status', '=', 1);
                $builder->where("customer_id", "=", "0");
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'num_items_sold',
        'total_sales',
        'tax_total',
        'shipping_total',
        'net_total',
        'returning_customer',
        'comment_status',
        'payment_method',
        'status',
        'customer_id',
        'site_id'
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */


    protected $with = array('order_meta', 'details' , 'coupon', 'products');
    public function details()
    {
        return $this->hasMany(ProductMeta::class, "product_id", "ID");
    }
    public function order_meta()
    {
        return $this->hasMany(OrderMeta::class, "post_id", "ID");
    }

    public function products()
    {
        return $this->hasMany(ProductOrder::class, "order_id", "ID");
    }



    public function coupon()
    {
        return $this->hasMany(OrderCoupon::class, "order_id", 'ID');
    }
}
