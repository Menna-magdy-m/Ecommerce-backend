<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\CreateStoreRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuthService;
use App\Services\WishListService;
use App\Services\StoreService;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    private $service;

    public function __construct(StoreService $service)
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
     * @param Request $request
     * @return \App\Http\Responses\SuccessResponse
     */
    public function store(CreateStoreRequest $request)
    {
        return $this->ok($this->service->create($request->all()));
    }

    public function update(CreateStoreRequest $request, int $id)
    {
        return $this->ok($this->service->save($id, $request->all()));
    }
}

