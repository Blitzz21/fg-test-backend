<?php

// Adjust REQUEST_URI so the router inside public/api.php receives the correct path
$_SERVER['REQUEST_URI'] = '/' . ltrim($_GET['path'] ?? 'api', '/');

require __DIR__ . '/../public/api.php';
