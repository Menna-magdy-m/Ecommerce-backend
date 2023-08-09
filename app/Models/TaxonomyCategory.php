<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TaxonomyCategory extends Model
{
    protected $table = 'wp_term_taxonomy';
    protected $primaryKey = "term_taxonomy_id";
    public $timestamps = false;

    protected $fillable = [
        //
        'term_taxonomy_id',
        'term_id',
        'description',
        'taxonomy',
        'parent','count'
    ];

    protected $with = array('meta');

    public function meta()
    {
        return $this->hasMany(Category::class, "term_id", "ID");
    }

}
