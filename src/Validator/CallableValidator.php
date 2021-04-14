<?php

namespace App\Validator;


class CallableValidator
{

    public function type(string $varType, string $fieldValue): bool
    {
        if(is_numeric($fieldValue)) { //convert to number if possible and then check the type
            $fieldValue = (int) $fieldValue;
        }

        return gettype($fieldValue) === $varType ? true : false;
    }

    public function required(string $required, string $fieldValue): bool
    {
        return empty($fieldValue) && !$required ? true : false;
    }

    public function maxLength(string $maxLength, string $fieldValue): bool
    {
        return strlen($fieldValue) > $maxLength ? false : true;
    }

}