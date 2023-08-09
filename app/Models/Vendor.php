<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
class Vendor extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $table = 'wp_users';
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
                $builder->whereHas('meta');


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
        'user_login',
        'name',
        'user_email',
        'user_nicename',
        'phone',
        'user_pass',
        'user_status',
        'user_registered',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_pass',
        'remember_token',
        'user_registered',
        'user_activation_key',
        'user_status'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'role_id' => 'integer',
    ];
    protected $with = array('meta');
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
        return $this->hasMany(VendorMeta::class,"user_id","ID");
    }   public function product()
    {
        return $this->hasMany(Product::class,"post_author","ID");
    }
    public function photo()
    {
        return $this->hasMany(Attachment::class,"post_author","ID");
    }
}
