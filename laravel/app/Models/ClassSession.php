<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSession extends Model
{

    /** @use HasFactory<\Database\Factories\ClassSessionFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['start_time', 'end_time', 'class_id'];

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
            'start_time' => ['required', 'string', 'date'],
            'end_time' => ['nullable', 'string', 'date', 'after:start_time'],
            'class_id' => [
                'exists:classes,id' // Ensure the class_id exists in the classes table
            ],

        ];

        return array_merge($defaultRules, $overrides);
    }

    public function klass()
    {
        return $this->belongsTo(Klass::class, 'class_id');

    }
}
