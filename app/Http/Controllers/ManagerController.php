<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\StoreManagerRequest;
use App\Http\Requests\UpdateManagerRequest;
use App\Services\ManagerService;

class ManagerController extends Controller
{
    protected $service;

    public function __construct(ManagerService $service)
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

    public function store(StoreManagerRequest $request)
    {
        return $this->ok($this->service->create($request->all()));
    }

    public function update(UpdateManagerRequest $request, int $id)
    {
        return $this->ok($this->service->save($id, $request->all()));
    }

    public function destroy(int $id)
    {
        return $this->ok($this->service->delete($id));
    }


}
