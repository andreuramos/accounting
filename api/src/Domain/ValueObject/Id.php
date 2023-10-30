<?php

namespace App\Domain\ValueObject;

class Id
{
    public function __construct(private readonly ?int $id)
    {
    }

    public function getInt(): ?int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
