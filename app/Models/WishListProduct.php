<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class WishListProduct extends Model
{
    protected $table = 'wp_woodmart_wishlist_products';
    protected $primaryKey = 'ID';

    protected $fillable = [
        'product_id',
        'wishlist_id',
        'date_added',
        'on_sale'=>'1'
    ];

    protected static function booted()
    {
        static::addGlobalScope('accessDB', function (Builder $builder) {
        });
    }

    protected $with = array('product');


    public function product()
    {
        return $this->hasOne(product::class, "ID", "product_id");
    }
    public function wishlist()
    {
        return $this->hasOne(WishList::class,"ID",'wishlist_id');
    }


}
