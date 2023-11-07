<?php

namespace Service\Utilities;

use App\Service\Utilities\Calculator;
use PHPUnit\Framework\TestCase;

class CalculatorTest extends TestCase
{
    private Calculator $calculator;

    public function setUp(): void
    {
        parent::setUp();

        $this->calculator = new Calculator();
    }

    /**
     * @test
     * @testWith [100, 20, 120, "Calculate with positive percentage"]
     *           [100, -20, 80, "Calculate with negative percentage"]
     *           [120, 22, 146, "Calculate with flooring result"]
     *           [120, 23, 147, "Calculate with flooring result"]
     */
    public function test_sum_base_with_percent($base, $percentageIncrease, $expected, $message): void
    {
        $result = $this->calculator->sumBaseWithPercent($base, $percentageIncrease);

        $this->assertSame($expected, $result, $message);
    }
}
