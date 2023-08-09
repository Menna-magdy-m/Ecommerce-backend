<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class OrderDokan extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_dokan_orders';
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = false;

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
                //
                //  $privilege = $user->role()->get(['privilege'])->first();
                //  $privilege = (int)$privilege->privilege;
//                $builder->where(function (Builder $builder) use ($privilege) {
//                    $builder->whereHas('role', function (Builder $qb) use ($privilege) {
//                        $qb->where('privilege', '>=', $privilege);
//                    });
//                    if ($privilege > 2) {
//                        $builder->where('user_status', '=', User::status_active);
//                    }
//                });
                // $builder->orWhere('id', '=', )

            } else {
                //    $builder->where('user_status', '=', 1);
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'seller_id',
        'order_total',
        'net_amount',
        'order_status'
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

    protected $with = array();


    public function details()
    {
        return $this->hasMany(ProductMeta::class, "product_id", "ID");
    }

    public function product()
    {
        return $this->hasMany(ProductOrder::class, "order_id", "order_id");
    }

    public function item()
    {
        return $this->hasMany(ProductOrderItem::class, "order_id", "order_id");
    }
}
