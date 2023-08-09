<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class ProductPhoto extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'attachments';
    public $timestamps = false;
    protected $primaryKey = 'ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        //
         'content',	'title'	,'status',	'post_parent',	'path',	'parent_type',	'menu_order',	'post_mime_type'
    ];

    protected static function booted()
    {
        static::addGlobalScope('accessDB', function (Builder $builder) {
            $builder->where('parent_type', '=', 'product');
        });
    }

    // protected $with = array('photo');

    // public function photo()
    // {
    //     return $this->hasMany(Attachment::class, "ID", "meta_value");

    // }

}
