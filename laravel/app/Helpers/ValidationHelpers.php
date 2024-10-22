<?php

namespace App\Helpers;

class ValidationHelpers
{
    /**
     * Make all rules optional by removing 'required' validation.
     *
     * @param array $rules
     * @return array
     */
    public static function makeRulesOptional(array $rules): array
    {
        foreach ($rules as $key => &$rule) {
            if (is_array($rule)) {
                $rule = array_filter($rule, function ($value) {
                    return $value !== 'required';
                });
            } elseif ($rule === 'required') {
                $rule = '';
            }
        }

        return $rules;
    }
}
