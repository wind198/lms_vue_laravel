<?php

namespace App\Models;

use App\Constants\AppConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomSetting extends Model
{

    /** @use HasFactory<\Database\Factories\GenerationFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['is_global', 'dates_off', 'title', 'description'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    public static function getRules(array $overrides = []): array
    {
        $defaultRules = [
            'is_global' => ['required', 'boolean'],
            'dates_off' => ['nullable', 'array'],
            'dates_off.*' => ['integer', 'min:0', 'max:365'],
            'title' => ['required', 'string', 'max:' . 2 * AppConstants::MAX_TITLE_LENGTH],
            'description' => ['nullable', 'string', 'max:' . AppConstants::MAX_DESCRIPTION_LENGTH],

        ];

        return array_merge($defaultRules, $overrides);
    }

    public function opentimes()
    {
        return $this->hasMany(RoomOpenTime::class, 'room_setting_id');
    }
}
