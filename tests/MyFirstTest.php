<?php # tests/MyFirstTest.php

use PHPUnit\Framework\TestCase;

class MyFirstTest extends TestCase
{
    public function test_true_be_or_not_true_be()
    {
        $this->assertTrue(true);
    }

    public function this_is_not_a_test()
    {
        // This will never be executed!
        $this->assertTrue(false);
    }
}
