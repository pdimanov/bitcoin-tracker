<?php

namespace App\Enum;

enum TimeInSeconds: int
{
    case ONE_MINUTE = 60;
    case ONE_HOUR   = 3600;
    case ONE_DAY    = 86400;
}
