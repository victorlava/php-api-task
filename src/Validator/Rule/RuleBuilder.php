<?php

namespace App\Validator\Rule;

class RuleBuilder
{

    public $rules;

    public function __construct()
    {
        $this->rules = [];

        $this->selectedField = '';

        $this->fieldNames = [];

        $this->fieldsRequired = [];

        $this->fieldsType = [];

        $this->errorMessages = [];
    }

    public function fields(array $fieldNames): self
    {
        $this->fieldNames = $fieldNames;
        return $this;
    }

    public function required(): self
    {
        $this->fieldsRequired = $this->fieldNames;
        return $this;
    }

    public function type(string $typeName): self
    {
        $this->fieldsType[$this->selectedField] = $typeName;
        return $this;
    }

    public function field(string $fieldName): self
    {
        $this->selectedField = $fieldName;
        return $this;
    }

    public function errorMessage(string $message): self
    {
        $this->errorMessages[$this->selectedField] = $message;
        return $this;
    }

    public function build()
    {
        $rules = ['rules' => []];

        foreach($this->fieldNames as $fieldName) {
            $this->rules[$fieldName] = $rules;

            if($this->isFieldRequired($fieldName)) {
                $this->rules[$fieldName]['rules']['required'] = 'true';
            }

            if(array_key_exists($fieldName, $this->fieldsType)) {
                $this->rules[$fieldName]['rules']['type'] = $this->fieldsType[$fieldName];
            }

            if(array_key_exists($fieldName, $this->errorMessages)) {
                $this->rules[$fieldName]['error']= $this->errorMessages[$fieldName];
            }

        }

    }

    private function isFieldRequired(string $fieldName): bool
    {
        return in_array($fieldName, $this->fieldsRequired) ? true : false;
    }

}