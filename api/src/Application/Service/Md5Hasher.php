<?php

namespace App\Application\Service;

class Md5Hasher implements HasherInterface
{
    public function hash(string $str): string
    {
        return md5($str . "salt");
    }
}
