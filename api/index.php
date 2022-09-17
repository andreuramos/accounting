<?php

try {
    $response = require_once './config/bootstrap.php';
    $response->send();
} catch (Throwable $throwable) {
    echo $throwable->getMessage();
}
