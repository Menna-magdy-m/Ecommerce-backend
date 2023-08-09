<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class TermMeta extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_termmeta';
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

    protected static function booted()
    {
        static::addGlobalScope('accessDB', function (Builder $builder) {
            $builder->where('meta_key', '=', 'thumbnail_id');
        });
    }

    protected $with = array('photo');

    public function photo()
    {
        return $this->hasMany(Attachment::class, "ID", "meta_value");

    }

}
