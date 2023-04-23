<?php

namespace App\Shared\Infrastructure;

use Symfony\Component\HttpFoundation\Response;

class ApiResponse extends Response
{
    public function __construct(
        array $data,
        int $status = 200
    ) {
        $content = json_encode($data, JSON_THROW_ON_ERROR);
        $headers = ['Content-Type' => 'application/json'];
        parent::__construct($content, $status, $headers);
    }
}
