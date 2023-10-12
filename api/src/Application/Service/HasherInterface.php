<?php

namespace App\Application\Service;

interface HasherInterface
{
    public function hash(string $str): string;
}
