<?php

namespace App\Shared\Application\Service;

interface HasherInterface
{
    public function hash(string $str): string;
}
