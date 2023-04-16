<?php

namespace App\Shared\Infrastructure\Service;

use App\Shared\Application\Service\HasherInterface;

class Md5Hasher implements HasherInterface
{
    public function hash(string $str): string
    {
        return md5($str . "salt");
    }
}
