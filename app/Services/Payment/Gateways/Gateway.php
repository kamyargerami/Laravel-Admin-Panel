<?php


namespace App\Services\Payment\Gateways;


abstract class Gateway
{
    // Amount of transaction
    // Description that stores in table and sends to gateway
    // Parameters will added to callback url

    abstract function redirect($amount, $callback, $description = null, $mobile = null, $email = null): array;

    abstract function verify($request): array;
}
