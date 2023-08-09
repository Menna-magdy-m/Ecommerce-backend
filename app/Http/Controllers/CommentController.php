<?php

namespace App\Http\Controllers;

use App\Dtos\SearchQuery;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SearchRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Comment;
use App\Services\AuthService;
use App\Services\CommentService;
use App\Services\WishListService;
use App\Services\CartService;
use Illuminate\Validation;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    private $service;

    public function __construct(CommentService $service)
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

    public function store(UpdateCommentRequest $request)
    {
        return $this->ok($this->service->create($request->all()));
    }

    public function update(UpdateCommentRequest $request, int $id)
    {
        return $this->ok($this->service->save($id, $request->all()));
    }

    public function update_publish(Request $request, int $id)
    {
        $validated = $request->validate([
             "comment_approved" => "required|string|boolean",
        ]);
        return $this->ok($this->service->update_publish($id, $request->all()));
    }


    public function destroy(int $id)
    {
        return $this->ok($this->service->delete($id));
    }

}

