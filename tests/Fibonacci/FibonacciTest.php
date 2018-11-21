<?php

use PHPUnit\Framework\TestCase;
use SixtyNine\Fibonacci\Fibonacci;

class FibonacciTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage The number must be positive
     */
    public function testArgumentMustBePositive()
    {
        $fibonacci = new Fibonacci();
        $fibonacci->calc(-1);
    }

    public function testFirstNumberOfSequenceIsZero()
    {
        $fibonacci = new Fibonacci();
        $res = $fibonacci->calc(0);
        // Difference between assertEquals and assertSame (the second is a strict equality)
        $this->assertEquals(false, $res);
        $this->assertSame(0, $res);
    }

    public function testSecondNumberOfSequenceIsOne()
    {
        $fibonacci = new Fibonacci();
        $res = $fibonacci->calc(1);
        $this->assertSame(1, $res);
    }

    public function testAssertSomeMoreNumbers()
    {
        $fibonacci = new Fibonacci();
        $this->assertSame(1, $fibonacci->calc(2));
        $this->assertSame(2, $fibonacci->calc(3));
        $this->assertSame(3, $fibonacci->calc(4));
        $this->assertSame(5, $fibonacci->calc(5));
    }

    /** @dataProvider evenSomeMoreNumbersProvider */
    public function testEvenSomeMoreNumbers(int $number, int $expectedResult)
    {
        $fibonacci = new Fibonacci();
        $this->assertSame($expectedResult, $fibonacci->calc($number));
    }

    public function evenSomeMoreNumbersProvider()
    {
        return [
            [0, 0],
            [1, 1],
            [2, 1],
            [3, 2],
            [4, 3],
            [5, 5],
            [6, 8],
            [7, 13],
            [8, 21],
            [9, 34],
            [10, 55],
        ];
    }
}
