<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

/**
 * error response
 */
class ErrorResponse extends JsonResponse
{
    /**
     *
     * @param $message
     * @param $status
     */
    public function __construct(string $message = '',  $statusCode = -1)
    {
        $data = [];
        $data['message'] = $message;
        $data['statusCode'] = $statusCode;
        parent::__construct($data, 400);
    }
}
