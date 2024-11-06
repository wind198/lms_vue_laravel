<?php

namespace App\Helpers;

use App\Constants\AppConstants;
use Illuminate\Database\Schema\Blueprint;

class AppHelpers
{
    public static function addDescriptiveFieldToTable(Blueprint $table)
    {

        $table->string('title', AppConstants::MAX_TITLE_LENGTH)->index();
        $table->text('description')->index();
    }
}