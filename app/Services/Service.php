<?php

namespace App\Services;

use App\Dtos\Result;

abstract class Service
{
    public function ok( $data, string $message = ''): Result
    {
        return new Result($data, $message, 0);
    }
}
