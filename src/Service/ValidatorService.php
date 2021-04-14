<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class ValidatorService
{

    private $validationRules;

    public function __construct()
    {
        $this->error = '';
        $this->rules =  ['id' => [    'rules' => ['type' => 'integer', 'required' => 'true'],
                                                'error' => 'No item'],
                                    'data' => [ 'rules' => ['type' => 'string', 'required' => 'true', 'maxlength' => 255],
                                                 'error' => 'No data parameter']
                                    ];

    }

    public function isRequestValid(Request $request): bool
    {
        $index = 0;

        foreach($request->request->keys() as $fieldName)
        {
            $keyIndex = $index;
            $rules = $this->rules[$fieldName]['rules']; // if fieldName id then grab rules from fieldnamearray

            foreach($rules as $ruleKey => $ruleValue) {
                $methodName = $this->createMethodName($ruleKey);

                if(!$this->$methodName($ruleValue, $request->request->get($fieldName))) { // dynamicly calling method and validating the value
                    $this->setError($this->rules[$fieldName]['error']);
                    break;
                }
            }
            $index++;
        }

        return false;
    }

    public function error()
    {
        return ['error' => $this->getError()];
    }

    private function createMethodName(string $ruleName): string
    {
        return 'validate' . ucfirst($ruleName);
    }

    private function setError($error)
    {
        $this->error = $error;
    }

    private function getError()
    {
        return $this->error;
    }

    private function validateType(string $varType, string $fieldValue): bool
    {
        if(is_numeric($fieldValue)) { //convert to number if possible and then check the type
            $fieldValue = (int) $fieldValue;
        }

        return gettype($fieldValue) === $varType ? true : false;
    }

    private function validateRequired(string $required, string $fieldValue): bool
    {
        return empty($fieldValue) && !$required ? true : false;
    }

    private function validateMaxLength(string $maxLength, string $fieldValue): bool
    {
        return strlen($fieldValue) > $maxLength ? false : true;
    }

}