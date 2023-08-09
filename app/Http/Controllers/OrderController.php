<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuthService;
use App\Services\OrderPostService;
use App\Services\WishListService;
use App\Services\OrderService;

class OrderController extends Controller
{
    private $service;

    public function __construct(OrderService $service)
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

    public function show(int $id)
    {
        return $this->ok($this->service->get($id));
    }

    public function store(OrderRequest $request)
    {
        return $this->ok($this->service->create($request->all()));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        return $this->ok($this->service->update2($id, $request->all()));
    }

}

