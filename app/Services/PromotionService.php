<?php

namespace App\Services;


use App\Models\Attachment;
use App\Models\Product;
use App\Models\Promotion;
use Carbon\Carbon;

class PromotionService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = [
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content',
        'post_title',
        'post_excerpt',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping',
        'pinged',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count'
    ];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = ['post_title'];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['post_title', 'post_type', 'post_name'];
    /**
     *
     */
    protected array $with = ['photo'];


    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Promotion::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {

        return parent::prepare($operation, $attributes);
    }

    /**
     * Create Promotion
     */
    public function createPromotion(array $attributes): \App\Dtos\Result
    {
        $date = Carbon::now();
        $attributes['post_author'] = 1;
        $attributes['post_date'] = $date->toDateTimeString();
        $attributes['post_date_gmt'] = $date->toDateTimeString();
        $attributes['post_content'] = $attributes['promotion_discount'];
        $attributes['post_title'] = $attributes['promotion_title'];
        $attributes['post_excerpt'] = "dfgdfg";
        $attributes['post_status'] = "publish";
        $attributes['comment_status'] = "active";
        $attributes['ping_status'] = 'gfvhjfgh';
        $attributes['post_password'] = "pass";
        $attributes['post_name'] = "name";
        $attributes['to_ping'] = "gfhgfh";
        $attributes['pinged'] = "hjhjhjh";
        $attributes['post_modified'] = $date->toDateTimeString();
        $attributes['post_modified_gmt'] = $date->toDateTimeString();
        $attributes['post_content_filtered'] = "jkhkhkh";
        $attributes['guid'] = "kjjhk";
        $attributes['menu_order'] = 2;
        $attributes['post_type'] = "promotion";
        $attributes['post_mime_type'] = "gfhghgh";
        $attributes['comment_count'] = 20;

        // store attachment
        $attributes['post_parent'] = 0;

        // store promotion
        $record = parent::store($attributes);

        if ($record instanceof Promotion) {
            $record->post_parent = $record->ID;
            $record->update();

            return $this->ok($record, "promotion:created:done");
        }
    }


}
