<?php

namespace App\Http\Requests;

use App\Constants\AppConstants;
use App\Helpers\ValidationHelper;
use App\Helpers\ValidationHelpers;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStudentRequest extends FormRequest
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
        $student = $this->route('student');

        $rules['email'] =
            ['required', 'string', 'email', 'max:' . AppConstants::$MAX_EMAIL_LENGTH, Rule::unique('users')->ignore($student->id),];

        // Make each field optional by modifying the rules
        return ValidationHelpers::makeRulesOptional($rules);
    }
}
