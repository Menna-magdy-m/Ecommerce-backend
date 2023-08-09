<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;


class Product extends Authenticatable
{
    use HasApiTokens, HasFactory;
    use SoftDeletes;

    protected $table = 'products';
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
                //$header = $request->header('Authorization');
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
                $builder->where('status', '=', "publish");

            } else {
                //    $builder->where('user_status', '=', 1);
                $builder->where('status', '=', "publish");
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
        'title',
        'user_id',
        'content',
        'status',
        'comment_status',
        'name',
        'post_content_filtered',
        'menu_order',
        'comment_count',

        'sku',
        'virtual',
        'downloadable',
        'min_price',
        'max_price',
        'onsale',
        'stock_quantity',
        'stock_status',
        'rating_count',
        'average_rating',
        'total_sales',
        'tax_status',
        'tax_class',
        'photo_id',
        'site_id',
        'min_order_quantity','max_order_quantity', 'weight', 'keywords'
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

    protected $with = array('details', 'Photo', 'category','local');

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
        return $this->hasMany(ProductVariation::class, "post_parent", "ID");
    }


    public function Photo()
    {
        return $this->hasMany(ProductPhoto::class, "post_parent", "ID");
    }

    public function meta()
    {
        return $this->hasMany(post_meta::class, "post_id", "ID");
    }

    public function details()
    {
        return $this->hasMany(ProductMeta::class, "product_id", "ID");
    }

    public function translate()
    {
        return $this->hasMany(ProductTranslate::class, "product_id", "ID");
    }

    public function local()
    {
        return $this->hasOne(ProductTranslate::class, "product_id", "ID")->where('language',app()->getLocale());
    }
    public function wishlist()
    {
        return $this->belongsTo(WishList::class, 'product_id', 'ID', WishListProduct::class);
    }

    // public function category()
    // {
    //     return $this->hasMany(ProductCategory::class, "object_id", "ID");
    // }

    public function category()
    {
        return $this->belongsToMany(Category::class, "product_category" , "product_id" ,"category_id")->withPivot('term_order');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, "comment_post_ID", "ID");
    }

    public function author()
    {
        return $this->hasone(User::class, "ID", "user_id");
    }

    public function site()
    {
        return $this->hasone(Site::class, "ID", "site_id");
    }
}
