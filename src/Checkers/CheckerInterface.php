<?php

namespace App\Checkers;

interface CheckerInterface
{
    public function check(string $value): bool;
}
