<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class OrderItemMeta extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $table = 'wp_woocommerce_order_itemmeta';
    public $timestamps = false;
    protected $primaryKey = 'meta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //
        'meta_id',
        'order_item_id',
        'meta_key',
        'meta_value',
    ];

    protected $with = array();

}
