<?php


namespace App\Services;


use App\Models\Store;

class StoreService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = ['option_value', 'autoload'];

    /**
     * updatable field is a field which can be filled during updating the record
     */
protected array $updatables = ['option_value', 'autoload'];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['option_name'];
    /**
     *
     */
    protected array $with = ['logo'];

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Store::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        return parent::prepare($operation, $attributes);
    }

    /**
     * @return \App\Dtos\Result
     * @throws \Exception
     */
    public function create(array $attributes)
    {
        $attributes['autoload'] = 'yes';

        $data = Store::query()->where('option_name', '=', $attributes['option_name']);

        if (isset($data->get()[0])) {
            $data->update($attributes);
        } else {
            throw new \Exception("option_name:not_found");
        }

        return $this->ok($data , "store:created:done");
    }

    public function save($id, array $attributes)
    {

        $data = Store::query()->where('option_name', '=', $attributes['option_name'])->get();


        if (isset($data[0])) {
            $fields = $this->prepare('update', $attributes);
            $record = parent::find($id);
            $record = parent::update($id, $attributes);
        } else {
            throw new \Exception("option_name:not_found");
        }

        return $this->ok($record, "store:updated:done");
    }


}
