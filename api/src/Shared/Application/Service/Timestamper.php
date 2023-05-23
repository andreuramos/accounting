<?php

namespace App\Shared\Application\Service;

class Timestamper
{
    public function __invoke(): \DateTime
    {
        return new \DateTime();
    }
}
