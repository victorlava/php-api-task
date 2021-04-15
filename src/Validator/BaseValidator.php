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
        $this->error = false;
        $this->builder = $ruleBuilder;
    }

    public function isRequestValid(Request $request): bool
    {

        $this->builder->build();

        $index = 0;

        foreach($this->builder->getRules() as $fieldName => $fieldRules)
        {
            $keyIndex = $index;

            foreach($fieldRules['rules'] as $ruleKey => $ruleValue) {
                $methodName = $this->createMethodName($ruleKey);

                if(!$this->validate->$methodName($ruleValue, $request->get($fieldName))) { // dynamicly calling method and validating the value
                    $this->setError($this->builder->getError($fieldName));
                    break;
                }

            }

            $index++;
        }

        return $this->error ? false : true;
    }

    public function error()
    {
        return ['error' => $this->getError()];
    }

    private function createMethodName(string $ruleName): string
    {
        return $ruleName;
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