<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\CartRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\CartService;

class CartController extends Controller
{
    private $service;

    public function __construct(CartService $service)
    {
        $this->service = $service;
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return $this->ok($this->service->get($id));
    }

    /**
     * store
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function store(CartRequest $request)
    {
        $attributes = $request->all();

        return $this->ok($this->service->create($attributes));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        return $this->ok($this->service->save($id, $request->all()));
    }

    public function destroy(int $id)
    {
        return $this->ok($this->service->delete($id));
    }


}

