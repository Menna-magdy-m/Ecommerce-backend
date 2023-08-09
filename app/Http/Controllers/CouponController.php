<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Http\Requests\CouponRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\CouponService;

class CouponController extends Controller
{
    private $service;

    public function __construct(CouponService $service)
    {
        $this->service = $service;
    }


    public function add(CouponRequest $request)
    {
        $attributes = $request->all();

        return $this->ok($this->service->add($attributes));
    }

    public function update(UpdateUserRequest $request, int $id)
    {
        return $this->ok($this->service->save($id, $request->all()));
    }

    public function destroy(int $id)
    {
        return $this->ok($this->service->delete($id));
    }


}

