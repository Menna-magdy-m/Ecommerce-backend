<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $table = 'wp_addresses';
    protected $primaryKey = 'id';
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
                $builder->where("user_id","=",$user->ID);

            } else {
                //    $builder->where('user_status', '=', 1);
                $builder->where("user_id","=",$user->ID);


            }
        });
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'street1',
        'street2',
        'country',
        'city',
        'zipcode',
        'longitude',
        'latitude',
        'phone',
        'type',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
        'user_id' => 'integer',
        'site_id' => 'integer',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * user
     */
    public function user()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * site
     */
    public function site()
    {
        return $this->belongsTo(Site::class);
    }
}
