<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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
            $user = auth()->user();
            if ($user instanceof User) {


                if ($user->isAdmin()) {


                } else {
                    $builder->where('user_status', '=', 1);

                }
            } else {
                $builder->where('user_status', '=', 1);
            }
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
    public const status_suspended = 2;
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
    protected $with = array();


    /**
     * role
     */
    public function toLightWeightArray()
    {

        $result = $this->toArray();
       // $customer = $this->isCustomer();

        $phone = $this->hasPhone();
       // if ($phone)
            $result["user_phone"] = $phone;
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

    public function customer()
    {
        return $this->hasOne(Customer::class, "user_id", 'ID');
    }

    public function customer_id()
    {
        return $this->customer()->get()->toArray()[0]["customer_id"];

    }

    public function isCustomer()
    {
        $customer = $this->customer()->get();
        if (is_array($customer))
            if ($customer[0] instanceof Customer) {
                return $customer[0];
            } else return false;
    }

    public function isAdmin()
    {
        //return true;
        $admin = $this->role()->get()[0];
        if ($admin instanceof UserMetaRole) {
            $role = unserialize($admin["meta_value"]);
            if (isset($role["manager"]) || isset($role["administrator"])) {
                if ($role["manager"] == true) {
                    return $admin;

                }
            }
        }
        return false;
    }
    public function hasPhone()
    {
        //return true;
        $phone = $this->phone()->get();
       // dd($phone);
        if(isset($phone[0]))
        if ($phone[0] instanceof UserPhone) {

            return $phone[0]->meta_value;

        }
        return "";
    }
    public function phone()
    {
        return $this->hasMany(UserPhone::class, "user_id", "ID");
    }


}
