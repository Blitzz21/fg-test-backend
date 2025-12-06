<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Core\Response;
use App\Controllers\ProductsController;
use App\Controllers\CartController; 
use App\Controllers\UploadController; 
use App\Controllers\DesignsController;

$router = new Router();

// -------------------------
// CORS HEADERS (DEV)
// -------------------------
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

$allowedOrigins = [
    'http://localhost:3000',
    'http://localhost:5173', // if you ever use Vite's default port
];

if (in_array($origin, $allowedOrigins, true)) {
    header("Access-Control-Allow-Origin: {$origin}");
    header('Access-Control-Allow-Credentials: true');
} else {
    // Or for local dev you can do:
    // header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Origin: *');
}

header('Access-Control-Allow-Methods: GET, POST, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// -------------------------
// register routes
// -------------------------
$router->get('/api/products', [ProductsController::class, 'index']);
$router->post('/api/cart/lines', [CartController::class, 'addLine']);
$router->post('/api/upload', [UploadController::class, 'upload']);
$router->get('/api/designs', [DesignsController::class, 'index']);
$router->post('/api/designs', [DesignsController::class, 'store']);

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Throwable $e) {
    Response::json(['error' => $e->getMessage()], 500);
}
