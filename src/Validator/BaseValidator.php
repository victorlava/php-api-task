<?php

namespace App\Validator;

use App\Rule\ValidationRuleBuilder;
use Symfony\Component\HttpFoundation\Request;

class BaseValidator
{

    private $validationRules;

    public function __construct(ValidationRuleBuilder $ruleBuilder)
    {
        $this->validate = new CallableValidator();
        $this->error = '';
        $this->rules = $ruleBuilder->getRules();

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