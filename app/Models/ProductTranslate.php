<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class ProductTranslate extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'product_translate';
    public $timestamps = true;
    protected $primaryKey = 'ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'language',
        'title',
        'content',
        'short_description',

    ];


    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'ID');
    }

}
