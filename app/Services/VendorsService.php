<?php

namespace App\Services;

use App\Models\Vendor;

class VendorsService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = ['user_login',
        'name',
        'user_email',
        'user_nicename',
        'phone',
        'user_pass',
        'user_status',
        'user_registered',];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    protected array $updatables = ['user_nicename', 'user_login', 'user_email', 'user_pass', 'user_status'];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['user_login', 'user_email', 'user_status'];
    /**
     *
     */
    protected array $with = ['product'];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Vendor::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        if (isset($attributes['user_pass'])) {
        }
        if ($operation === 'store') {
            if (!isset($attributes['user_status'])) {
                $attributes['user_status'] = Vendor::status_active;
            }
            if (!isset($attributes['language'])) {
                $attributes['language'] = 'en';
            }
        }
        return parent::prepare($operation, $attributes);
    }


}
