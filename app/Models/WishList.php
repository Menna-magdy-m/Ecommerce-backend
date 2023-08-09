<?php


namespace App\Models;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    protected $table = 'wp_woodmart_wishlists';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'date_created'
    ];

    protected static function booted()
    {
        static::addGlobalScope('accessDB', function (Builder $builder) {
            $user = auth()->user();
            if ($user instanceof User) {

               $builder->where('user_id', '=', $user->ID);

            } else {
                $builder->where('user_id', '=', 0);
            }
        });
    }

    protected $with = array('products');


    public function products()
    {
        return $this->belongsToMany(Product::class,WishListProduct::class,'wishlist_id');
    }
    public function wishlist()
    {
        return $this->hasMany(WishListProduct::class, 'wishlist_id','ID');
    }


}
