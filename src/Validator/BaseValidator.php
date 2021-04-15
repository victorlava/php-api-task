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
        $this->isValid = false;
        $this->errorMessage = '';
        $this->errorCode = '';
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

                $this->isValid = true;
            }

            $index++;
        }

        return $this->isValid;
    }

    public function errorMessage(): array
    {
        return ['error' => $this->getErrorMessage()];
    }

    public function errorCode(): int
    {
        return $this->getErrorCode();
    }

    private function createMethodName(string $ruleName): string
    {
        return $ruleName;
    }

    private function setError(array $error): void
    {
        $this->errorMessage = $error['message'];
        $this->errorCode = $error['code'];
        $this->isValid = false;
    }

    private function setErrorCode(int $code): void
    {
        $this->errorCode = $code;
    }

    private function getErrorMessage()
    {
        return $this->errorMessage;
    }

    private function getErrorCode()
    {
        return $this->errorCode;
    }
}