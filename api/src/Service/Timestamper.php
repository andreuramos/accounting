<?php

namespace App\Service;

class Timestamper
{
    public function __invoke(): \DateTime
    {
        return new \DateTime();
    }
}
