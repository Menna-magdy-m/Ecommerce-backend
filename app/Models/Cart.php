<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Cart extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_woocommerce_sessions';
    public $timestamps = false;
    protected $primaryKey = 'session_id';
    public $incrementing = true;

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'session_id',
        'session_key',
        'session_value',
        'session_expiry'
    ];

}
