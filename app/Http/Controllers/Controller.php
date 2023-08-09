<?php

namespace App\Http\Controllers;

use App\Dtos\Result;
use App\Http\Responses\ErrorResponse;
use App\Http\Responses\SuccessResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * echo
     */
    protected function echo(string $key): string {
        return $key;
    }

    /**
     * ok
     */
    protected function ok(Result $result) {
        return new SuccessResponse($result);
    }

    /**
     * error
     */
    protected function error(string $message, int $status = 400) {
        return new ErrorResponse($message, $status);
    }

    public function show(int $id)
    {
        return $this->ok($this->service->get($id));
    }
}
