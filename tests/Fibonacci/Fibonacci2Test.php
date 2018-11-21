<?php

use PHPUnit\Framework\TestCase;
use SixtyNine\Fibonacci\Fibonacci2;

class Fibonacci2Test extends TestCase
{
    /** @var Fibonacci2 */
    protected $fibonacci;

    public function setUp()
    {
        parent::setUp();

        $this->fibonacci = new Fibonacci2();
    }

    public function testFirstNumberOfSequenceIsZero()
    {
        $res = $this->fibonacci->calc(0);
        // Difference between assertEquals and assertSame (the second is a strict equality)
        $this->assertEquals(false, $res);
        $this->assertSame(0, $res);
    }

    public function testSecondNumberOfSequenceIsOne()
    {
        $res = $this->fibonacci->calc(1);
        $this->assertSame(1, $res);
    }

    public function testAssertSomeMoreNumbers()
    {
        $this->assertSame(1, $this->fibonacci->calc(2));
        $this->assertSame(2, $this->fibonacci->calc(3));
        $this->assertSame(3, $this->fibonacci->calc(4));
        $this->assertSame(5, $this->fibonacci->calc(5));
    }

    /** @dataProvider evenSomeMoreNumbersProvider */
    public function testEvenSomeMoreNumbers(int $number, int $expectedResult)
    {
        $this->assertSame($expectedResult, $this->fibonacci->calc($number));
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
