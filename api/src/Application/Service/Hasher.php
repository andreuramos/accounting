<?php

namespace App\Application\Service;

class Hasher implements HasherInterface
{
    public function hash(string $str): string
    {
        return md5($str . "salt");
    }
}
