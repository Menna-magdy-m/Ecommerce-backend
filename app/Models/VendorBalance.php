<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class VendorBalance extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_dokan_vendor_balance';
    public $timestamps = false;
    protected $primaryKey = 'id';

    protected static function booted()
    {
        static::addGlobalScope('accessDB', function (Builder $builder) {
            $builder->where('meta_key', '=', "wp_capabilities")->where('meta_value', 'LIKE', '%seller%');
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //
        'vendor_id',
        'trn_id',
        'meta_value',
        'perticulars',
        'debit',
        'credit',
        'status',
        'trn_date',
        'balance_date'
    ];

    protected $with = array();

}
