<?php

namespace App\Http\Requests;

use App\Constants\AppConstants;
use App\Helpers\ValidationHelpers;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoomRequest extends FormRequest
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
        return ValidationHelpers::makeRulesOptional(Room::getRules());
    }
}
