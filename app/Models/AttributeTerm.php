<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class AttributeTerm extends Model
{
    protected $table = 'attribute_term';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'attribute_id',
        'menu_order',
        'status'
    ];



    public function attribute()
    {
        return $this->belongsTo(Attribute::class, "attribute_id" , "ID");
    }
}
