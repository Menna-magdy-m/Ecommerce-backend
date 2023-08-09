<?php


namespace App\Models;


class ProductStatus
{

    /**
     * ProductStatus constructor.
     * @param string $class
     */
    public const administrator = "administrator";
    public const manager = "manager";
    public const customer = "customer";
    public function __construct(string $class)
    {
    }
}
