<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class OrderMeta extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_postmeta';
    public $timestamps = false;
    protected $primaryKey = 'meta_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //
        'meta_key',
        'meta_value',
        'post_id'
    ];

    protected $with = array();

}
