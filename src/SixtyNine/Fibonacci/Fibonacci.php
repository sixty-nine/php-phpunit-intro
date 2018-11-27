<?php

namespace SixtyNine\Fibonacci;

/**
 * Fibonnaci numbers, the recursive version.
 */
class Fibonacci
{
    public function calc(int $number) : int
    {
        // Fibonacci numbers are also defined for negative integers, but let's pretend they are not.
        if ((int)$number < 0) {
            throw new \InvalidArgumentException('The number must be positive');
        }

        if ($number < 2) {
            return $number;
        }

        return $this->calc($number - 1) + $this->calc($number - 2);
    }
}
