<?php

namespace App\Validator;


class CallableValidator
{

    public function type(string $varType, $fieldValue): bool
    {

        if($varType == 'integer') {
            if(is_numeric($fieldValue)) { //convert to number if possible and then check the type
                $fieldValue = (int) $fieldValue;
            }
        }

        return gettype($fieldValue) === $varType ? true : false;
    }

    public function required(string $required, $fieldValue): bool
    {
        return !empty($fieldValue) ? true : false;
    }

    public function maxLength(string $maxLength, $fieldValue): bool
    {
        return strlen($fieldValue) > $maxLength ? false : true;
    }

}