<?php

namespace App\Service\Utilities;

use Illuminate\Support\Carbon;

class TimeIntervalBuilder
{
    public function create(int $interval)
    {
        return (new Carbon())->addHours($interval);
    }
}
