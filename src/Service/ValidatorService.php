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
        $this->rules =  ['id' => [    'rules' => ['type' => 'int', 'required' => 'true'],
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

                $methodName = 'validate' . ucfirst($ruleKey);
                if(!$this->$methodName($ruleValue, $fieldName)) { // dynamicly calling method and validating the value
                    dump('ssdf');
                }

//               if(!$this->validate{$ruleKey})($ruleValue)) { // construct validateType(int), validateRequired(true)
//
//                break;
//                }
            }
            $index++;
        }

        return false;
    }

    public function jsonError()
    {
        return $this->error;
    }

    private function validateType(string $type, string $fieldName)
    {
        return false;
    }

    private function validateRequired(string $type, string $fieldName)
    {
        return false;
    }

    private function validateMaxLength(string $type, string $fieldName)
    {
        return false;
    }

}