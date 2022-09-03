<?php

$response = require_once './config/bootstrap.php';
$response->send();

echo '<pre>' . print_r($response,1) . '</pre>';
