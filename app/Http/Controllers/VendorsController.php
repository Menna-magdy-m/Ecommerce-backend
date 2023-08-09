<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\ProductService;
use App\Services\OrderService;
use App\Services\VendorsService;

class VendorsController extends Controller
{
    private $service;

    public function __construct(VendorsService $service)
    {
        $this->service = $service;
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
}
