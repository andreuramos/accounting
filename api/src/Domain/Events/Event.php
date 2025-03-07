<?php

namespace App\Domain\Events;

abstract class Event implements \JsonSerializable
{
    public function __construct(
        protected readonly string $name,
        private readonly \DateTimeInterface $timestamp,
    ) {
    }
}