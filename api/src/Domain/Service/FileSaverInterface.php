<?php

namespace App\Domain\Service;

interface FileSaverInterface
{
    public function __invoke(string $content, string $filename): string;
}