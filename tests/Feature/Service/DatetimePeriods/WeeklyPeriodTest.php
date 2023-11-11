<?php

namespace Tests\Feature\Service\DatetimePeriods;

use App\Service\DatetimePeriods\WeeklyPeriod;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WeeklyPeriodTest extends TestCase
{
    /**
     * @testWith [0]
     *           [1]
     *           [4]
     */
    public function test_build($shift): void
    {
        $this->freezeTime(function () use ($shift) {
            $expectedStartDatetime = Carbon::now()->subWeeks($shift + 1);
            $expectedEndDatetime   = Carbon::now()->subWeeks($shift);
            $expected              = [
                'startDatetime' => $expectedStartDatetime,
                'endDatetime'   => $expectedEndDatetime
            ];

            $weeklyPeriod = new WeeklyPeriod();
            $result       = $weeklyPeriod->build($shift);

            $this->assertEquals($expected, $result);
        });
    }
}
