<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Carbon;

class CustomerService extends ModelService
{
    /**
     * storable field is a field which can be filled during creating the record
     */
    protected array $storables = [
        'customer_id',
        'user_id',
        'username',
        'first_name',
        'last_name',
        'email',
        'date_last_active',
        'date_registered',
        'country',
        'postcode',
        'city',
        'state'
    ];

    /**
     * updatable field is a field which can be filled during updating the record
     */
    
    protected array $updatables = [
        
        'username',
        'first_name',
        'last_name',
        'email',
        'date_last_active',
        'date_registered',
        'country',
        'postcode',
        'city',
        'state'
    ];

    /**
     * searchable field is a field which can be search for from keyword parameter in search method
     */
    protected array $searchables = ['username',
    'first_name',
    'last_name',
    'email',
    'country',
    'city',
    'state'];
    /**
     *
     */
    protected array $with = [];

   

    public function builder(): \Illuminate\Database\Eloquent\Builder
    {
        return Customer::query();
    }

    /**
     * prepare
     */
    protected function prepare(string $operation, array $attributes): array
    {
        return parent::prepare($operation, $attributes);
    }


    /**
     * Display listing of the customers.
     *
     * @param \App\Http\Requests\SearchRequest
     * @return \Illuminate\Http\Response
     */
    public function index(SearchRequest $request)
    {
        return $this->ok($this->service->search(SearchQuery::fromJson($request->all())));
    }

    /**
     * create a new Customer
     */
    public function store(array $attributes): Customer
    {
        $record = parent::store($attributes);
        
        if ($record instanceof Customer) {
           

        }
        return $record;
    }


    

    


}
