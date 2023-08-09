<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\ProductVariation;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ProductVariationService extends ModelService
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
    protected array $updatables = ['user_login', 'user_email', 'user_pass', 'user_status'];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['user_login', 'user_email', 'user_status'];
    /**
     *
     */
    protected array $with = ['meta', 'category', 'variation'];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return ProductVariation::query();
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
    public function store(array $attributes): Cart
    {
        $record = parent::store($attributes);
        // TODO: sites attribute value
        if ($record instanceof Cart) {
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
        return $this->ok($record, "users:update:done");
    }

    public function findProduct($id)
    {
        DB::enableQueryLog();

        $qb = $this->builder();
        if ($id == null) {
            return ('records:find:errors:not_found:null');
        } else if (is_array($id)) {
            if (!count($id)) {
                return ('records:find:errors:not_found::empty');
            }
            foreach ($id as $k => $value) {
                $qb = $qb->where($k, '=', $value);
            }

        } else {

            $qb = $qb->where(($qb->getModel())->getKeyName(), '=', $id);
        }
        $qb = $qb->with($this->with);
        $qb = $qb->first();
        if (!$qb instanceof ProductVariation) {
            return 'records:find:errors:not_found:null2';
        }
        return $qb;
    }

    /**
     * delete inner
     */

    public function destroy($id)
    {
        $record = $this->find($id);
        if ($record instanceof User) {
            $addresses = $record->addresses()->get()->all();
            foreach ($addresses as $address) {
                if ($address instanceof \App\Models\Address) {
                    $address->delete();
                }
            }
        }
        return parent::destroy($id);
    }
}
