<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
class Comment extends Authenticatable
{
    use HasApiTokens, HasFactory;
    protected $table = 'wp_comments';
    public $timestamps = false;
    protected $primaryKey = 'comment_ID';
    protected $id = 'comment_ID';
    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    protected static function booted()
    {

    }


    public const comment_type = ['comment'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        //
        'comment_ID',
        'comment_post_ID',
        'comment_author',
        'comment_author_email',
        'comment_approved',
        'user_id',
        'comment_date',
        'comment_date_gmt',
        'comment_content',
        'comment_type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'comment_author_email'
        ,'comment_author_url',
        'comment_author_IP'
        ,'comment_date_gmt',
        'comment_type','',''

    ];
    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];
    protected $with = array("user");
    /**
     * role
     */
    public function toLightWeightArray()
    {
        $result = $this->toArray();
        return $result;
    }
    public function user(){
        return $this->hasOne(User::class,"ID","user_id");
    }

}
