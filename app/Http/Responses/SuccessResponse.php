<?php

namespace App\Http\Responses;

use App\Dtos\Result;
use Illuminate\Http\JsonResponse;

/**
 * success response
 */
class SuccessResponse extends JsonResponse
{
    /**
     * @param $data
     * @param $message
     * @param $status
     */
    public function __construct(Result $result)
    {
        parent::__construct($result, 200);
    }
}
