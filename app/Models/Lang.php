<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Enums\LangEnum;

class Lang extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_options';
    public $timestamps = false;
    protected $primaryKey = 'option_id';
    public $incrementing = true;

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope('accessDB', function (Builder $builder) {

            //    $builder->where('user_status', '=', 1);
            $builder->where('option_name', 'WPLANG');
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        
        'option_value',
    ];
    

    protected $langs = [
        'lang' => LangEnum::class
    ];
}
