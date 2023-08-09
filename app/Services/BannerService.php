<?php


namespace App\Services;


use App\Models\Attachment;
use App\Models\Banner;
use App\Models\WishListProduct;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class BannerService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = ['post_title',
        'post_content',
        'post_author',
        'post_status',
        'post_type',];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = ['post_title',
        'post_content',
        'post_author',
        'post_status',
        'post_type',];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['post_title', 'post_content'];
    /**
     *
     */
    protected array $with = [];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Banner::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {

        return parent::prepare($operation, $attributes);
    }

    public function create(array $attributes)
    {
        $banner = Banner::where('post_title', $attributes['post_title'])->first();
        if ($banner) {
            return $this->save($banner->ID, $attributes);
        }
        $attributes["post_date"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
        $attributes["post_date_gmt"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
        $attributes["post_author"] = auth()->user()->ID;
        $attributes["comment_status"] = "closed";
        $attributes["post_type"] = "banner";

        return parent::create($attributes);
    }

    /**
     * update banner
     */
    public function save($banner_ID, array $attributes)
    {
        $attributes["post_modified"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');
        $attributes["post_modified_gmt"] = Carbon::parse(date('Y-m-d H:i:s'))->format('Y-m-d H:i:s');

        return parent::save($banner_ID, $attributes);
    }

    /**
     *update banner
     *
     */
    public function update2(array $attributes)
    {
        //dd($attributes);
        $record = Banner::first();

        if ($attributes["lang"]) {

            $record->update(['option_value' => $attributes["lang"]]);

        }


        return $this->ok($record, "language updated");
    }


}
