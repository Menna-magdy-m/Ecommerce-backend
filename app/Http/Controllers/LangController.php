<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuthService;
use App\Services\WishListService;
use App\Services\LangService;
use App\Http\Requests\UpdateLangRequest;

class LangController extends Controller
{
    private $service;

    public function __construct(LangService $service)
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
        //dd($this->service);
        return $this->ok($this->service->search(SearchQuery::fromJson($request->all())));
    }

    public function update(UpdateLangRequest $request)
    {
        return $this->ok($this->service->update2($request->all()));
    }

}

