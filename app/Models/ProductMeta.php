<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class ProductMeta extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'product_meta';
    public $timestamps = false;
    protected $primaryKey = 'ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'attribute_term_id',
        'sku',
        'virtual',
        'downloadable',
        'min_price',
        'max_price',
        'onsale',
        'stock_quantity',
        'stock_status',
        'rating_count',
        'average_rating',
        'total_sales',
        'tax_status',
        'tax_class',
    ];

    protected $with = array('attribute_term');

    public function attribute_term()
    {
        return $this->belongsTo(AttributeTerm::class, 'attribute_term_id', 'ID');
    }

}
