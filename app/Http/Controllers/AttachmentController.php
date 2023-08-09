<?php


namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Dtos\Result;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateAttachmentRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\AuthService;
use App\Services\AttachmentService;
use App\Services\WishListService;
use App\Http\Requests\AttachmentRequest;

class AttachmentController extends Controller
{
    private $service;

    public function __construct(AttachmentService $service)
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

    
    public function store(AttachmentRequest $request)
    {
        return $this->ok(new Result($this->service->create($request->all())),'test');
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

    
    
    public function update(UpdateAttachmentRequest $request, int $id)
    {
        return $this->ok($this->service->update2($id, $request->all()));
    }

    public function destroy( int $id)
    {
        return $this->ok($this->service->delete($id));
    }

}

