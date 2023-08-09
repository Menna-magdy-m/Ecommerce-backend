<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attributes';
    protected $primaryKey = 'ID';
    public $timestamps = false;

    protected $fillable = [

        'attribute_name'	,
        'attribute_label'	,
        'attribute_type'	,
        'attribute_orderby'	,
        'attribute_public'
    ];




}
