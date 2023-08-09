<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Store extends Authenticatable
{
    use HasApiTokens, HasFactory;


    protected $table = 'wp_options';
    public $timestamps = false;

    protected $fillable = [
        'option_name',
        'option_value',
    ];
    protected $primaryKey = 'option_id';
    public $incrementing = true;


    protected $with = array('logo');

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('accessDB', function (Builder $builder) {

            //    $builder->where('user_status', '=', 1);
            $builder->whereIn('option_name', array('logo-photo', "blogname", 'blogdescription', 'ActiveType', 'OwnerType', 'telephone', 'facebook', 'instagram', 'tweeter', 'tiktok', 'youtube', 'linkedin', 'site_color', 'product_offer','woocommerce_currency','WPLANG','admin_email'));
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    public function logo()
    {
        return $this->hasMany(Attachment::class, "ID", "option_value");
    }
}
