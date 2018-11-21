<?php

use SixtyNine\Fibonacci\Fibonacci3;

class Fibonacci3Test extends Fibonacci2Test
{
    public function setUp()
    {
        parent::setUp();
        $this->fibonacci = new Fibonacci3();
    }
}
