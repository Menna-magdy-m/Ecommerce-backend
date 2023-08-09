<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class ProductVariationAttribute extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_wc_product_attributes_lookup';
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

        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'product_or_parent_id',
        'taxonomy',
        'term_id',
        'is_variation_attribute',
        'in_stock'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $with = array("attribute_name");

    public function attribute_name()
    {
        return $this->hasOne(Term::class, "term_id", "term_id");
    }

}
