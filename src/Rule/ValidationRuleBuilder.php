<?php

namespace App\Rule;

class ValidationRuleBuilder extends AbstractRuleBuilder
{
    use RuleBuilderTrait;

    public function build(): void
    {
        $rules = ['rules' => []];

        foreach($this->fieldNames as $fieldName) {
            $this->rules[$fieldName] = $rules;

            $this->setRequired($fieldName);
            $this->setError($fieldName);
            $this->setType($fieldName);
        }
    }

}