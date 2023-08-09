<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuthService;
use App\Services\WishListService;

class ReviewController extends Controller
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
     * activate
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function activate(int $id)
    {
        return $this->ok($this->service->activate($id));
    }

    /**
     * suspend
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function suspend(int $id)
    {
        return $this->ok($this->service->suspend($id));
    }

    /**
     * register
     *
     * @param \App\Http\Requests\LoginRequest $request
     * @return \Illuminate\Http\Response
     */
    public function register(RegisterRequest $request)
    {
        return $this->ok($this->service->register($request->all()));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        return $this->ok($this->service->update2($id, $request->all()));
    }

}

