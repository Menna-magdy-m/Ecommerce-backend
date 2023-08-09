<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\CategoriesService;

class CategoryController extends Controller
{
    private $service;

    public function __construct(CategoriesService $service)
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

  public function store(StoreCategoryRequest $request)
  {
      return $this->ok($this->service->create($request->all()));
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

    public function showProduct(int $id)
    {
        return $this->ok($this->service->categoryProducts($id));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        return $this->ok($this->service->update2($id, $request->all()));
    }

    public function show_translate($id)
    {
        return $this->ok($this->service->find_translate($id), 'records:get:done');
    }
}

