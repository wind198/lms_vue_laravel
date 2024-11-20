<?php

namespace App\Http\Requests;

use App\Helpers\ValidationHelpers;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateManyUsersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = User::getRules();

        unset($rules['email']);

        $rules = ValidationHelpers::makeRulesOptional($rules);
        $rules['ids']=[
            'ids' => ['required', 'array', 'min:1'],
            'ids.*' => ['integer'],
        ];
        return $rules;
    }
}
