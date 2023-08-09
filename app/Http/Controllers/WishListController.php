<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\StoreWeshListRequest;
use App\Http\Requests\StoreWishListRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuthService;
use App\Services\WishListService;

class WishListController extends Controller
{
    private $service;

    public function __construct(WishListService $service)
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


    public function store(StoreWishListRequest $request)
    {
        return $this->ok($this->service->create( $request->all()));
    }
    public function update(StoreWishListRequest $request, int $id)
    {
        return $this->ok($this->service->update2($id, $request->all()));
    }
    public function destroy( int $id)
    {
        return $this->ok($this->service->delete($id));
    }

}

