<?php

namespace App\Exception;

class ValidationException extends \Exception
{

    public function __construct(private array $errors)
    {
    }
}
