<?php

namespace App\Services;

use App\Models\Address;

class UserAdressService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = [
        'street1',
        'street2',
        'country',
        'city',
        'zipcode',
        'longitude',
        'latitude',
        'phone',
        'type',
        'user_id'
    ];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = [
        'street1',
        'street2',
        'country',
        'city',
        'zipcode',
        'longitude',
        'latitude',
        'phone',
        'type'
    ];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['street1', 'street2', 'city', 'country', 'phone'];
    /**
     *
     */
    protected array $with = [];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Address::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        return parent::prepare($operation, $attributes);
    }


    /**
     * create a new address
     */
    public function create(array $attributes)
    {

        $attributes["user_id"]= auth()->user()->ID;
        ;
        return parent::create($attributes);
    }
    public function save($comment_ID, array $attributes)
    {
        return parent::save($comment_ID, $attributes);
    }


}
