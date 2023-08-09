<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_wc_customer_lookup';
    public $timestamps = false;
    protected $primaryKey = 'customer_id ';

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('accessDB', function (Builder $builder) {

        });
    }

    /**
     * NEW: user registered without otp confirmation
     * UNVERIFIED: user confirmed otp but wait for admin or manager to activate
     * ACTIVE: user register or created and activated by admin or manager
     * SUSPENDED: user disabled by admin
     */
    public const statuses = ['NEW', 'UNVERIFIED', 'ACTIVE', 'SUSPENDED'];
    public const status_new = 'NEW';
    public const status_unverified = 'UNVERIFIED';
    public const status_active = 1;
    public const status_suspended = 'SUSPENDED';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //
        'customer_id',
        'user_id',
        'username',
        'first_name',
        'last_name',
        'email',
        'date_last_active',
        'date_registered',
        'country',
        'postcode',
        'city',
        'state'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];
    protected $with = array();

    /**
     * role
     */
    public function toLightWeightArray()
    {
        $result = $this->toArray();
        return $result;
    }

    public function meta()
    {
        return $this->hasMany(UserMeta::class, "user_id", "ID");
    }

    public function wishlist()
    {
        return $this->belongsToMany(WishList::class, WishList::class, "user_id");
    }

    public function role()
    {
        return $this->hasMany(UserMetaRole::class, "user_id", 'ID');
    }

    public function user()
    {
        return $this->hasOne(User::class, "ID", 'user_id');
    }

}
