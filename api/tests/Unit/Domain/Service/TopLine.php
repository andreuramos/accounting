<?php

namespace Test\Unit\Domain\Service;

class TopLine
{
    public function __construct(
        public readonly int $base,
        public readonly int $rate,
        public readonly int $tax,
    ) {
    }
}