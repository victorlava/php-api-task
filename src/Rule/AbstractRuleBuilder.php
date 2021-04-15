<?php

namespace App\Rule;

abstract class AbstractRuleBuilder implements RuleBuilderInterface
{
    /** @var string */
    public $selectedField;

    /** @var array */
    public $rules;

    /** @var array */
    public $fieldNames;

    /** @var array */
    public $fieldsRequired;

    /** @var array */
    public $fieldsType;

    /** @var array */
    public $errorMessages;

    public function __construct()
    {
        $this->selectedField = '';
        $this->rules = [];
        $this->fieldNames = [];
        $this->fieldsRequired = [];
        $this->fieldsType = [];
        $this->errorMessages = [];
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function fields(array $fieldNames): self
    {
        $this->fieldNames = $fieldNames;
        return $this;
    }

    public function field(string $fieldName): self
    {
        $this->selectedField = $fieldName;
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

    public function errorMessage(string $message): self
    {
        $this->errorMessages[$this->selectedField] = $message;
        return $this;
    }
}