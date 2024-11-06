<?php

namespace App\Models;

use App\Constants\AppConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'description', 'year'];

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
            'year' => ['required', 'integer', 'min:1900'],
        ];

        return array_merge($defaultRules, $overrides);
    }
}
