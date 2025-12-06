<?php

require __DIR__ . '/../public/api.php';

$effectiveUri = '/'.ltrim($_GET['path'] ?? 'api', '/');
$router->dispatch($_SERVER['REQUEST_METHOD'], $effectiveUri);