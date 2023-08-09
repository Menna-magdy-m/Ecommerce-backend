<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class ProductVariation extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_posts';
    public $timestamps = false;
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
                $builder->where('post_status', '=', "publish")->where("post_type", "=", "product_variation");

            } else {
                //    $builder->where('user_status', '=', 1);
                $builder->where('post_status', '=', "publish")->where("post_type", "=", "product_variation");
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //
        'post_title',
        'post_parent'

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

    protected $with = array('details', 'Photo', 'attribute');

    /**
     * role
     */
    public function toLightWeightArray()
    {
        $result = $this->toArray();
        return $result;
    }

    public function variation()
    {
        return $this->hasMany(Product::class, "post_parent", "ID");
    }

    public function Photo()
    {
        return $this->hasMany(ProductPhoto::class, "post_id", "ID");
    }

    public function meta()
    {
        return $this->hasMany(post_meta::class, "post_id", "ID");
    }

    public function details()
    {
        return $this->hasMany(ProductMeta::class, "product_id", "ID");
    }

    public function wishlist()
    {
        return $this->belongsTo(WishList::class, 'product_id', 'ID', WishListProduct::class);
    }

    public function category()
    {
        return $this->hasMany(ProductCategory::class, "object_id", "ID");
    }

    public function attribute()
    {
        return $this->hasMany(ProductVariationAttribute::class, "product_id", "ID");
    }
}
