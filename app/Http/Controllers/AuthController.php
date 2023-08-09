<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    private $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    /**
     * Login
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        return $this->ok($this->service->login($request->all()));
    }


    /**
     * Me
     *
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        return $this->ok($this->service->me());
    }

    /**
     * Logout
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        return $this->ok($this->service->logout());
    }
}
