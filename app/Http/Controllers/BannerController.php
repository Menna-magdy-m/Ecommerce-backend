<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\BannerRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuthService;
use App\Services\BannerService;
use App\Services\WishListService;

class BannerController extends Controller
{
    private $service;

    public function __construct(BannerService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @param \App\Http\Requests\SearchRequest
     * @return \App\Http\Responses\SuccessResponse
     */
    public function index(SearchRequest $request)
    {
        return $this->ok($this->service->search(SearchQuery::fromJson($request->all())));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \App\Http\Responses\SuccessResponse
     */
    public function show(int $id)
    {
        return $this->ok($this->service->get($id));
    }
    /**
     * activate
     *
     * @param int $id
     * @return \App\Http\Responses\SuccessResponse
     */
    public function activate(int $id)
    {
        return $this->ok($this->service->activate($id));
    }

    /**
     * suspend
     *
     * @param int $id
     * @return \App\Http\Responses\SuccessResponse
     */
    public function suspend(int $id)
    {
        return $this->ok($this->service->suspend($id));
    }

    /**
     * register
     *
     * @param \App\Http\Requests\LoginRequest $request
     * @return \App\Http\Responses\SuccessResponse
     */
    public function register(RegisterRequest $request)
    {
        return $this->ok($this->service->register($request->all()));
    }

    
    
    public function update(BannerRequest $request, int $id)
    {
        return $this->ok($this->service->update2($id, $request->all()));
    }

    public function store(BannerRequest $request)
    {
        return $this->ok($this->service->create($request->all()));
    }
}

