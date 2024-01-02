<?php

namespace App\Application\UseCase\Form303;

class Manual303FormUseCase
{
    public function __invoke(Manual303FormCommand $request): Importable303Form
    {
        return new Importable303Form();
    }
}