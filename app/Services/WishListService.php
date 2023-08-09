<?php


namespace App\Services;


use App\Models\User;
use App\Models\WishList;

class WishListService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = ['user_id',
        'name',];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = ['name'];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['name'];
    /**
     *
     */
    protected array $with = [];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return WishList::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        if (isset($attributes['user_pass'])) {
        }
        return parent::prepare($operation, $attributes);
    }

    public function create(array $attributes)
    {
        $user = auth()->user();
        $attributes["user_id"] = $user->ID;
        if ($user instanceof User) {
            $user->wishlist()->detach();
        }
        $wishlist = parent::store($attributes);
        if ($wishlist instanceof WishList) {

            $wishlist->products()->attach($attributes["product_ids"]);
        }
        return $this->ok($wishlist, 'records:create:done');

    }

}
