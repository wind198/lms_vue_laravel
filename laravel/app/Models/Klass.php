<?php

namespace App\Models;

use App\Constants\AppConstants;
use Database\Factories\KlassFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Klass extends Model
{
    /** @use HasFactory<\Database\Factories\KlassFactory> */
    use HasFactory;


    protected $table = 'classes';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return KlassFactory::new();
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'description', 'course_id'];

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
            'course_id' => [
                'exists:courses,id'
            ],

        ];

        return array_merge($defaultRules, $overrides);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function sessions()
    {
        return $this->hasMany(ClassSession::class, 'class_id');
    }

}
