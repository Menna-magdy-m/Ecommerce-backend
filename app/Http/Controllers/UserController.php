<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\ProductService;
use App\Services\OrderService;
use App\Services\UserService;
use App\Services\VendorsService;

class UserController extends Controller
{
    private $service;

    public function __construct(UserService  $service)
    {
        $this->service = $service;
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
