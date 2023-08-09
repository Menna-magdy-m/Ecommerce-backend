<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class ProductCategory extends Authenticatable
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


    protected $with = array('category','taxonomy');

    public function taxonomy()
    {
        return $this->hasMany(TaxonomyCategory::class, "term_taxonomy_id", "term_taxonomy_id");

    }
    public function category()
    {
        return $this->belongsToMany(Category::class,TaxonomyCategory::class,'term_taxonomy_id','term_id','term_id');
    }

}
