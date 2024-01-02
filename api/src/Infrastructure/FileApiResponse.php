<?php

namespace App\Infrastructure;

use Symfony\Component\HttpFoundation\Response;

class FileApiResponse extends Response
{
    public function __construct(
        string $fileContent,
        string $fileName,
        string $contentType,
        int $status = 200
    ) {
        $headers = [
            'Content-Type' => $contentType,
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];
        parent::__construct($fileContent, $status, $headers);
    }
}