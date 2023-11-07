<?php

namespace Tests\Feature\Service\Utilities;

use App\Service\Utilities\TimeIntervalBuilder;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TimeIntervalBuilderTest extends TestCase
{
    private TimeIntervalBuilder $intervalBuilder;

    public function setUp(): void
    {
        parent::setUp();

        $this->intervalBuilder = new TimeIntervalBuilder();
    }

    /**
     * @testWith [1]
     *           [6]
     *           [24]
     */
    public function test_create($interval): void
    {
        $this->freezeTime(function (Carbon $time) use ($interval) {
            $date = $this->intervalBuilder->create($interval);
            $this->travel($interval)->hours();

            $this->assertEquals(new Carbon(), $date);
        });
    }
}
