<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;

class BaseValidator
{

    private $validationRules;

    public function __construct(CallableValidator $validationMethods)
    {
        $this->validate = $validationMethods;
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

                if(!$this->validate->$methodName($ruleValue, $request->request->get($fieldName))) { // dynamicly calling method and validating the value
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
        return ucfirst($ruleName);
    }

    private function setError($error)
    {
        $this->error = $error;
    }

    private function getError()
    {
        return $this->error;
    }
}