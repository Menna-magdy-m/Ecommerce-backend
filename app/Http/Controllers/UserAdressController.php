<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\StoreAdressRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Comment;
use App\Services\AddressService;
use App\Services\AuthService;
use App\Services\WishListService;
use App\Services\CartService;
use App\Services\OrderService;

class UserAdressController extends Controller
{
    private $service;

    public function __construct(AddressService $service)
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

    public function store(StoreAdressRequest $request)
    {
        return $this->ok($this->service->create($request->all()));
    }
    public function update(StoreAdressRequest $request, int $id)
    {
        return $this->ok($this->service->save($id, $request->all()));
    }    public function destroy( int $id)
    {
        return $this->ok($this->service->delete($id));
    }

}

