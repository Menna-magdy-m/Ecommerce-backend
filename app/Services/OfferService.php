<?php

namespace App\Services;

use App\Dtos\SearchQuery;
use App\Models\Offer;
use App\Models\Store;
use Illuminate\Database\Eloquent\Builder;

class OfferService extends ModelService
{
    protected array $storables = ['option_name', 'option_value', 'autoload'];

    protected array $updatables = ['option_name', 'option_value', 'autoload'];

    protected array $searchables = ['option_name'];

    protected array $with = ['logo'];

    public function builder(): Builder
    {
        // TODO: Implement builder() method.
        return Offer::query();
    }

    protected function prepare(string $operation, array $attributes): array
    {
        return parent::prepare($operation, $attributes);
    }

    /**
     * Create New Offer
     */
    public function createOffer(array $attributes)
    {
        $session = $this->get_option_key('product_offer');
        $attributes['option_value'] = serialize($attributes['option_value']);

        if ($session) {
            $id_offer = ($session->option_id);
            return $this->save($id_offer, $attributes);
        } else {
            $record = $this->store($attributes);

            return $this->ok($record, "offer:created:done");
        }

    }

    /**
     * Update Offer
     */
    public function updateOffer($id, array $attributes)
    {
        $this->find($id);

        $attributes['option_value'] = serialize($attributes['option_value']);

        return $this->save($id, $attributes);
    }

    /**
     * Delete Offer
     */
    public function deleteOffer(int $id)
    {
        // get the option_id
        $this->find($id);

        // delete the option
        $record = $this->delete($id);

        return $this->ok($record, "offer:deleted:done");
    }


    /* -------------------------------------
     * *********** Helper Functions ******
     * -------------------------------------
     */

    // Search for offer
    public function get_option_key(string $option_key)
    {
        $result = $this->search(SearchQuery::fromJson(
            ["offset" => "0",
                "limit" => "23",
                "sort" => [
                    "column" => "name",
                    "order" => "asc"],
                "fields" => [
                    "option_name" => [
                        "value" => $option_key
                    ]
                ]
            ]));
        return $result->data[0];
    }

}
