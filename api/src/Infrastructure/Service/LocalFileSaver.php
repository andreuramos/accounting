<?php

namespace App\Infrastructure\Service;

use App\Domain\Service\FileSaverInterface;

class LocalFileSaver implements FileSaverInterface
{
    public function __construct(
        private readonly string $basepath
    ) {
    }

    public function __invoke(string $content, string $filename): string
    {
        file_put_contents($this->basepath . $filename, $content);
        return $this->basepath . $filename;
    }
}
