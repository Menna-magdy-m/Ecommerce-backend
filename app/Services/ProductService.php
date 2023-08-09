<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class ProductService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = ['title',
    'user_id',
    'content',
    'status',
    'comment_status',
    'name',
    'post_content_filtered',
    'menu_order',
    'comment_count',

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
    'photo_id',
    'site_id'
    ];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = ['title',
    'user_id',
    'content',
    'status',
    'comment_status',
    'name',
    'post_content_filtered',
    'menu_order',
    'comment_count',

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
    'photo_id',
    ];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['name', 'sku', 'title','virtual'];
    /**
     *
     */
    protected array $with = ['meta', 'variation', 'comments'];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Product::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        if($operation=="search"){

        }
        return parent::prepare($operation, $attributes);
    }

    /**
     * create a new user
     */
    public function create(array $attributes)
    {
        $attributes["user_id"] = auth()->user()->ID;
        $attributes["site_id"] = $attributes["site_id"]?$attributes["site_id"]:1;
        $attributes["name"] = Str::slug($attributes["title"]);
        $attributes["post_content_filtered"] = "";
        $attributes["menu_order"] = "0";
        $attributes["comment_count"] = "0";
        return $this->ok($this->store($attributes), 'records:create:done');

    }

    public function store(array $attributes): Product
    {
        $record = parent::store($attributes);

        if ($record instanceof Product) {
            if(!empty($attributes["translate"])){
                foreach ($attributes["translate"] as $translate){
                    $record->translate()->create($translate);
                }
            }

            if(!empty($attributes["details"])){
                foreach ($attributes["details"] as $detail){
                    $record->details()->create($detail);
                }
            }
            if(!empty($attributes["category"])){
                $record->category()->attach($attributes["category"] );

            }

        }
        return $record;
    }

    /**
     *update user
     *
     */
    public function update2($id, array $attributes)
    {
        $fields = $this->prepare('update', $attributes);
        $record = $this->find($id);
        $record = parent::update($id, $attributes);

        if ($record instanceof Product) {
            if(!empty($attributes["translate"])){
                foreach ($attributes["translate"] as $translate){
                    $record->translate()->updateOrCreate(['language'=>$translate['language']],
                $translate);
                }
            }
            if(!empty($attributes["details"])){
                foreach ($attributes["details"] as $detail){
                    $d = $record->details()->find($detail['ID']);
                    if($d){
                        $d->update($detail);
                    }
                }

            }
            if(!empty($attributes["category"])){
                //$attributes["category"] =json_decode($attributes["category"] , true);
                $record->category()->detach();
                $record->category()->attach($attributes["category"] );
            }

        }
        return $this->ok($record, "product:update:done");
    }

    public function findProduct($id)
    {

        DB::enableQueryLog();

        $qb = $this->builder();
        if ($id == null) {
            return ('records:find:errors:not_found');

        } else if (is_array($id)) {
            if (!count($id)) {
                return ('records:find:errors:not_found');
            }
            foreach ($id as $k => $value) {
                $qb = $qb->where($k, '=', $value);
            }

        } else {

            $qb = $qb->where(($qb->getModel())->getKeyName(), '=', $id);
        }
        $qb = $qb->with($this->with);
        $qb = $qb->first();

        if (!$qb instanceof Product) {
            return 'records:find:errors:not_found';
        }
        return $qb;
    }

    /**
     * delete inner
     */

    public function destroy($id)
    {
        // $record = $this->find($id);
        // if ($record instanceof User) {
        //     $addresses = $record->addresses()->get()->all();
        //     foreach ($addresses as $address) {
        //         if ($address instanceof \App\Models\Address) {
        //             $address->delete();
        //         }
        //     }
        // }
        return parent::destroy($id);
    }

    public function find_translate($id)
    {
        $this->with[]="translate";
        //dd($this->with);
        return $this->get($id);
    }
}
