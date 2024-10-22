<?php

namespace App\Constants;

class AppConstants
{
    // Education backgrounds
    public static $HIGH_SCHOOL = 'HIGH_SCHOOL';
    public static $UNIVERSITY_STUDENT = 'UNIVERSITY_STUDENT';
    public static $GRADUATED = 'GRADUATED';
    public static $MASTER = 'MASTER';
    public static $PHD = 'PHD';
    public static $EDUCATION_BACKGROUNDS = [self::$HIGH_SCHOOL, self::$UNIVERSITY_STUDENT, self::$GRADUATED, self::$MASTER, self::$PHD];
    // User types
    public static $STUDENT_ROLE = 'student';
    public static $TEACHER_ROLE = 'teacher';
    public static $ROOT_ADMIN_ROLE = 'root_admin';
    public static $USER_TYPEs = ['student', 'teacher', 'root_admin'];
    // genders
    public static $MALE = 'MALE';
    public static $FEMALE = 'FEMALE';
    public static $GENDERS = [self::$MALE, self::$FEMALE];

    // validations
    public static $MAX_FIRST_NAME_LENGTH = 10;
    public static $MAX_PHONE_LENGTH = 25;
    public static $MAX_EMAIL_LENGTH = 50;
    public static $MAX_ADDRESS_LENGTH = 200;
}
