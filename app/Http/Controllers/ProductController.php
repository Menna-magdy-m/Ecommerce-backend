<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\ProductService;

class ProductController extends Controller
{
    private $service;

    public function __construct(ProductService $service)
    {
        $this->service = $service;
    }
    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\SearchRequest
     * @return \Illuminate\Http\Response
     */
    public function index(SearchRequest $request)
    {

        return $this->ok($this->service->search(SearchQuery::fromJson($request->all())));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        return $this->ok($this->service->get($id));
    }

    public function store(StoreProductRequest $request)
    {
        return $this->ok($this->service->create($request->all()));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        return $this->ok($this->service->update2($id, $request->all()));
        //return $this->ok($this->service->save($id, $request->all()));
    }

    public function show_translate($id)
    {
        return $this->ok($this->service->find_translate($id), 'records:get:done');
    }

}

