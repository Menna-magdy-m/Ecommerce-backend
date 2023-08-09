<?php

namespace App\Services;

use App\Models\Comment;
use Composer\Autoload\ClassLoader;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class CommentService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = [  'comment_post_ID',
        'comment_author',
        'comment_author_email',
        'comment_approved',
        'user_id',
        'comment_date',
        'comment_date_gmt',
        'comment_content',
        'comment_type',
        'user_id'
    ];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = [
        'comment_author',
        'comment_author_email',
        'comment_approved',
        'comment_date',
        'comment_date_gmt',
        'comment_content',
        'comment_type',];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['comment_post_ID','user_id','comment_author_email', 'comment_content'];
    /**
     *
     */
    protected array $with = [];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Comment::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        return parent::prepare($operation, $attributes);
    }




    /**
     * create a new user
     */
    public function create(array $attributes)
    {
        $attributes["comment_date"]=Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
        $attributes["comment_date_gmt"]=Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
        $attributes["comment_author"]=1;
        $attributes["comment_author_email"]=1;
        $attributes["comment_approved"]=1;
        return parent::create($attributes);
            }
    public function save($comment_ID,array $attributes)
         {
        $attributes["comment_date"]=Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
        $attributes["comment_author"]=1;
        $attributes["comment_author_email"]=1;
        $attributes["comment_approved"]=1;
        return parent::save($comment_ID,$attributes);
            }

    public function update_publish($comment_ID,array $attributes)
         {

        return parent::save($comment_ID,$attributes);
            }


}
