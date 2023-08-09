<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class CouponPost extends Authenticatable
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

                $builder->where("post_type", "=", "shop_coupon");
            } else {
                //    $builder->where('user_status', '=', 1);
                $builder->where("post_type", "=", "shop_coupon");
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
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status', '
        post_password',
        'post_name',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count'
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

    protected $with = array('details');

    public function meta()
    {
        return $this->hasMany(post_meta::class, "post_id", "ID");
    }

    public function details()
    {
        return $this->hasMany(CouponMeta::class, "post_id", "ID");
    }


}
