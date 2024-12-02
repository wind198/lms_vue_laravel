<?php

namespace App\Http\Requests;

use App\Helpers\ValidationHelpers;
use App\Models\RoomSetting;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomSettingRequest extends FormRequest
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
        return ValidationHelpers::makeRulesOptional(RoomSetting::getRules());
    }
}
