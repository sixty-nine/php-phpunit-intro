<?php

namespace SixtyNine\Fibonacci;

class Fibonacci
{
    public function calc(int $number) : int
    {
        if ((int)$number < 0) {
            throw new \InvalidArgumentException('The number must be positive');
        }

        if ($number < 2) {
            return $number;
        }

        return $this->calc($number - 1) + $this->calc($number - 2);
    }
}
