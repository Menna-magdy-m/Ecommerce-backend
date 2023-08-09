<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class TermTaxonomy extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'wp_term_relationships';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        //
        'object_id',
        'term_taxonomy_id',
        'term_order',
    ];



    protected $with = array('products');

    public function products()
    {
        return $this->hasMany(Product::class, "ID", "object_id");

    }

}
