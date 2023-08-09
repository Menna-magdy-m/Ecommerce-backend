<?php

namespace App\Dtos;

class Result
{
    /**
     *
     */
    public $statusCode;

    /**
     *
     */
    public $message;

    /**
     *
     */
    public $data;

    public function __construct( $data, string $message = '', int $statusCode = 0)
    {
        $this->data = $data;
        $this->message = $message;
        $this->statusCode = $statusCode;
    }
}
