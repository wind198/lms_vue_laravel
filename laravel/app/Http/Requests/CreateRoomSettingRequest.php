<?php

namespace App\Http\Requests;

use App\Models\Room;
use App\Models\RoomSetting;
use Illuminate\Foundation\Http\FormRequest;

class CreateRoomSettingRequest extends FormRequest
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
        $additionalRuls = [
            'open_times' => ['required', 'array', 'min:1'],
            'open_times.*' => ['required', 'json'],
            'open_times.*.start_time' => [
                'required',
                'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/', // Validates time in HH:MM format
            ],
            'open_times.*.end_time' => [
                'required',
                'regex:/^([01][0-9]|2[0-3]):([0-5][0-9])$/',
            ],
            'open_times.*.days' => ['required', 'array', 'min:1'],
            'open_times.*.days.*' => ['required', 'integer', 'min:0', 'max:7'],
        ];
        return array_merge(RoomSetting::getRules(), $additionalRuls);
    }
}
