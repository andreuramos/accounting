<?php

namespace App\Shared\Domain\ValueObject;

class Id
{
    public function __construct(private readonly ?int $id)
    {
    }

    public function getInt(): ?int
    {
        return $this->id;
    }
}
