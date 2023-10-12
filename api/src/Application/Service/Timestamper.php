<?php

namespace App\Application\Service;

class Timestamper
{
    public function __invoke(): \DateTime
    {
        return new \DateTime();
    }
}
