<?php

namespace App\Models;

use App\Constants\AppConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseMark extends Model
{
    /** @use HasFactory<\Database\Factories\CourseMarkFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'description', 'course_config_id', 'factor'];

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
            'title' => ['required', 'string', 'max:' . AppConstants::MAX_TITLE_LENGTH],
            'description' => ['nullable', 'string', 'max:' . AppConstants::MAX_DESCRIPTION_LENGTH],
            'factor' => ['required', 'integer', 'min:0'],
            'course_config_id' => [
                'exists:course_configs,id'
            ]
        ];

        return array_merge($defaultRules, $overrides);
    }

    public function courseConfig()
    {
        return $this->belongsTo(CourseConfig::class);
    }

}
