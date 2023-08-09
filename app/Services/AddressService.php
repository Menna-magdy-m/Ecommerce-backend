<?php

namespace App\Services;

use App\Models\Address;
use App\Models\Country;
use Illuminate\Container\Container;
use Illuminate\Validation\ValidationException;

class AddressService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = ['street1', 'street2', 'country', 'city', 'zip_code', 'longitude', 'latitude', 'user_id', 'phone','type'];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = ['street1', 'street2', 'country', 'city', 'zip_code', 'longitude', 'latitude','phone','type'];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = [];

    /**
     *
     */
    protected array $with = [];

    /**
     *
     */
    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Address::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        if (isset($attributes['country_id'])) {
            $countries = Container::getInstance()->get(CountryService::class);
            if ($countries instanceof CountryService) {
                $country = $countries->find($attributes['country_id']);
                //
                if (!$country instanceof Country) {
                    throw ValidationException::withMessages(['country_id' => 'addresses:country_id:errors:not_exists']);
                }
                if ($country->cities()->get()->where('id', '=', $attributes['city_id'])->isEmpty()) {
                    throw ValidationException::withMessages(['city_id' => 'addresses:city_id:errors:not_belongs_to_country']);
                }
            }
        }
        return parent::prepare($operation, $attributes);
    }
}
