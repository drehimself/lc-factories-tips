<?php

namespace App\Models;

class MathCustom
{
    public function add($num1, $num2)
    {
        return $num1 + $num2;
    }

    public function subtract($num1, $num2)
    {
        return $num1 - $num2;
    }

    public function multiply($num1, $num2)
    {
        return $num1 * $num2;
    }
}
