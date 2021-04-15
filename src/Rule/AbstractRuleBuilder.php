<?php

namespace App\Rule;

abstract class AbstractRuleBuilder implements RuleBuilderInterface
{
    /** @var string */
    public $selectedField;

    /** @var array */
    public $rules;

    /** @var array */
    public $rulesToDisable;

    /** @var array */
    public $fieldNames;

    /** @var array */
    public $fieldsRequired;

    /** @var array */
    public $fieldsType;

    /** @var array */
    public $error;

    public function __construct()
    {
        $this->selectedField = '';
        $this->rules = [];
        $this->rulesToDisable = [];
        $this->fieldNames = [];
        $this->fieldsRequired = [];
        $this->fieldsType = [];
        $this->error = [];
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function getError(string $fieldName): array
    {

        if(isset($this->rules[$fieldName]['error'])) {
            return $this->rules[$fieldName]['error'];
        }

        return $this->findDefaultError();
    }

    public function findDefaultError(): string
    {
        $error = array_search('error', $this->rules);
        return $error ?? '';
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

    public function disable(): self
    {
        $this->rulesToDisable[] = $this->selectedField;
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

    public function error(string $message, int $code): self
    {
        $this->error[$this->selectedField]['message'] = $message;
        $this->error[$this->selectedField]['code'] = $code;

        return $this;
    }
}