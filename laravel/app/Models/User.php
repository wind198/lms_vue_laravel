<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Constants\AppConstants;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Str;

class User extends Authenticatable
{


    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'email',
        'password',
        'user_type',
        'phone',
        'address',
        'education_background',
        'dob',
        'gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getRules()
    {
        return [
            'first_name' => ['required', 'string', 'max:' . AppConstants::MAX_FIRST_NAME_LENGTH],
            'last_name' => ['required', 'string', 'max:' . AppConstants::MAX_FIRST_NAME_LENGTH],
            'full_name' => ['required', 'string', 'max:' . AppConstants::MAX_FIRST_NAME_LENGTH * 2],
            'email' => ['required', 'string', 'email', 'max:' . AppConstants::MAX_EMAIL_LENGTH, 'unique:users'],
            'phone' => ['nullable', 'string', 'max:' . AppConstants::MAX_PHONE_LENGTH],
            'address' => ['nullable', 'string', 'max:' . AppConstants::MAX_ADDRESS_LENGTH],
            'education_background' => [
                'required',
                'string',
                'in:' . implode(',', AppConstants::EDUCATION_BACKGROUNDS)
            ],
            'generation_id' => [
                'nullable', // Make this field optional if needed
                'exists:generations,id' // Ensure the generation_id exists in the generations table
            ],
            'gender' => [
                'required',
                'string',
                'in:' . implode(',', AppConstants::GENDERS)
            ],
            'dob' => [
                'nullable',
                'date',
            ]
        ];
    }

    public static function generateRandomPassword($length = 8)
    {
        return Str::random($length);
    }

}
