<?php

namespace App\Constants;

class AppConstants
{
    // Education backgrounds
    public const HIGH_SCHOOL = 'HIGH_SCHOOL';
    public const UNIVERSITY_STUDENT = 'UNIVERSITY_STUDENT';
    public const GRADUATED = 'GRADUATED';
    public const MASTER = 'MASTER';
    public const PHD = 'PHD';
    public const EDUCATION_BACKGROUNDS_LIST = [
        self::HIGH_SCHOOL,
        self::UNIVERSITY_STUDENT,
        self::GRADUATED,
        self::MASTER,
        self::PHD
    ];

    // User types
    public const STUDENT_ROLE = 'student';
    public const TEACHER_ROLE = 'teacher';
    public const ROOT_ADMIN_ROLE = 'root_admin';
    public const USER_TYPES = [self::STUDENT_ROLE, self::TEACHER_ROLE, self::ROOT_ADMIN_ROLE];

    // Genders
    public const MALE = 'MALE';
    public const FEMALE = 'FEMALE';
    public const GENDERS = [self::MALE, self::FEMALE];

    // Validations
    public const MAX_FIRST_NAME_LENGTH = 20;
    public const MAX_PHONE_LENGTH = 25;
    public const MAX_EMAIL_LENGTH = 50;
    public const MAX_ADDRESS_LENGTH = 200;
    public const MAX_TITLE_LENGTH = 50;
    public const MAX_DESCRIPTION_LENGTH = 200;

    // Class session user roles
    public const CLASS_SESSION_TEACHER_ROLE = 'teacher';
    public const CLASS_SESSION_MENTOR_ROLE = 'mentor';
}
