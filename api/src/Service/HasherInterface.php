<?php

namespace App\Service;

interface HasherInterface
{
    public function hash(string $str): string;
}
