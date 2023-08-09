<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
class Attachment extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $table = 'attachments';
    public $timestamps = true;
    protected $primaryKey = 'ID';
    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    // protected static function booted()
    // {
    //     static::addGlobalScope('accessDB', function (Builder $builder) {
    //         $user = auth()->user();
    //         if ($user instanceof User) {

    //             $builder->where("post_type","=","attachment");

    //         } else {
    //         //    $builder->where('user_status', '=', 1);
    //             $builder->where("post_type","=","attachment");
    //             }
    //     });
    // }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //
        'content','user_id',	'title'	,'status',	'post_parent',	'path',	'parent_type',	'menu_order',	'post_mime_type'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */

    protected $with = array();
    /**
     * role
     */
    public function toLightWeightArray()
    {
        $result = $this->toArray();
        return $result;
    }
    public function parent()
    {
        return $this->belongsTo(Banner::class,"post_parent","ID");
    }
}
