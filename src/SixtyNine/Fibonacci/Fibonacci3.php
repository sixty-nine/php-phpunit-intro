<?php

namespace SixtyNine\Fibonacci;

/**
 * Fibonnaci numbers tail call version.
 */
class Fibonacci3
{
    public function calc(int $number, int $a = 0, int $b = 1) : int
    {
        return ($number > 0) ? $this->calc($number - 1, $b, $a + $b) : $a;
    }
}
