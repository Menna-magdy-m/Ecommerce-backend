<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    protected $table = 'wp_terms';
    protected $primaryKey = 'term_id';

    protected $fillable = [
        'name',
        'slug'
    ];

    public $timestamps = false;

    protected static function booted()
    {
        static::addGlobalScope('accessDB', function (Builder $builder) {
        });
    }

    protected $with = array();


}
