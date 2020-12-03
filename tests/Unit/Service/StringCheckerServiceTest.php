<?php

namespace App\Tests\Unit\Service;

use App\Service\StringCheckerService;
use PHPUnit\Framework\TestCase;

class StringCheckerServiceTest extends TestCase
{
    /**
     * @dataProvider stringValueProvider
     * @param string $value
     * @param bool $expectedResult
     */
    public function testSomething(string $value, bool $expectedResult)
    {
        $stringCheckerService = new StringCheckerService();
        $result = $stringCheckerService->doesStringMatchPattern($value);

        $this->assertEquals($expectedResult, $result);
    }

    public function stringValueProvider()
    {
        return [
            ["DEADBEEF!", true],
            ["ok!", false],
            ["baguette", false],
            ["oub#aguette", true],
            ["ouib#a##guette", true],
            ["ae#i", false],
            ["aaaaaaaaaaaeeeeeeeeeeeeeeeba######ebaguette", false],
            ["AeIOuUUoaUUoB#A###G###UE#################TtE#####EEEEEEEEEEEEEZ!", true],
            ["!", true],
            ["!a", false],
        ];
    }
}
