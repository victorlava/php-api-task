<?php

namespace App\Rule;

trait RuleBuilderTrait
{
    public function isRequired(string $fieldName): bool
    {
        return in_array($fieldName, $this->fieldsRequired) ? true : false;
    }

    public function isDisabled(string $fieldName): bool
    {
        return in_array($fieldName, $this->rulesToDisable) ? true : false;
    }

    public function hasType(string $fieldName): bool
    {
        return array_key_exists($fieldName, $this->fieldsType) ? true : false;
    }

    public function hasError(string $fieldName): bool
    {
        return array_key_exists($fieldName, $this->error) ? true : false;
    }

    public function setRequired(string $fieldName): void
    {
        if($this->isRequired($fieldName)) {
            $this->rules[$fieldName]['rules']['required'] = 'true';
        }
    }

    public function setDisabled(string $fieldName): void
    {
        if($this->isDisabled($fieldName)) {
            unset($this->rules[$fieldName]);
            unset($this->error[$fieldName]);
        }
    }

    public function setType(string $fieldName): void
    {
        if($this->hasType($fieldName)) {
            $this->rules[$fieldName]['rules']['type'] = $this->fieldsType[$fieldName];
        }
    }

    public function setError(string $fieldName): void
    {
        if($this->hasError($fieldName)) {
            $this->rules[$fieldName]['error']= $this->error[$fieldName];
        }
    }
}