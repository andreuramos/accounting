<?php

namespace Test\Unit\Domain\Service;

class BottomLine
{
    public function __construct(
        public readonly int $base,
        public readonly int $tax,
    ) {
    }
}