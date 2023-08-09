<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
    protected $table = 'wp_term_taxonomy';
    protected $primaryKey = "term_taxonomy_id";
    public $timestamps = false;

    protected $fillable = [
        //
        'term_id',
        'taxonomy',
        'description',
        'parent','count'
    ];

    // protected static function booted()
    // {
    //     static::addGlobalScope('accessDB', function (Builder $builder) {
    //         $builder->where("taxonomy", "=", "category")->orWhere('taxonomy', '=', 'product_cat');
    //     });
    // }
    protected $with = array('meta');

    public function meta()
    {
        return $this->hasMany(TermTaxonomy::class, "term_taxonomy_id", "term_id");
    }

}
