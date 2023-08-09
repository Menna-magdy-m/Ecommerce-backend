<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class CategoryTranslate extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'category_translate';
    public $timestamps = true;
    protected $primaryKey = 'ID';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'language',
        'name',
        'description',

    ];


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'ID');
    }

}
