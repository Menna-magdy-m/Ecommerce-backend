<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $table = 'categories';
    protected $primaryKey = 'ID';
    public $timestamps = true;

    protected $fillable = [
        'name'
        , 'slug',
        'parent'
    ];

    protected static function booted()
    {
        // static::addGlobalScope('accessDB', function (Builder $builder) {
        //   $builder->whereHas('taxonomy');
        // });
    }

    protected $with = array( 'photo','local');


    public function photo()
    {
        return $this->hasMany(Attachment::class, "post_parent", "ID")->where('parent_type','category');
    }


    public function product()
    {
        return $this->belongsToMany(Product::class, "product_category", "category_id" , "product_id")->withPivot('term_order');
    }
    public function translate()
    {
        return $this->hasMany(CategoryTranslate::class, "category_id", "ID");
    }

    public function local()
    {
        return $this->hasMany(CategoryTranslate::class, "category_id", "ID")->where('language',app()->getLocale());
    }
}
