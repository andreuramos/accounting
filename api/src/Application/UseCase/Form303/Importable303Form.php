<?php

namespace App\Application\UseCase\Form303;

class Importable303Form
{
    public function __construct(
        private readonly string $content
    ) {
    }
    
    public function __toString(): string
    {
        return $this->content;
    }
}