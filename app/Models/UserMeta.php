<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class UserMeta extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $table = 'wp_usermeta';
    public $timestamps = false;
    protected $primaryKey = 'meta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //
        'user_id',
        'meta_key',
        'meta_value',
    ];

    protected $with = array();

}
