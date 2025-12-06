<?php
// src/Core/Router.php

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    private function addRoute(string $method, string $path, $handler): void
    {
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        // Original full path, e.g. /NewShopify/public/api.php/api/products
        $fullPath = parse_url($uri, PHP_URL_PATH);

        // Script name, e.g. /NewShopify/public/api.php
        $scriptName = $_SERVER['SCRIPT_NAME'];

        // Remove the scriptName prefix from fullPath if present
        // So /NewShopify/public/api.php/api/products -> /api/products
        if (strpos($fullPath, $scriptName) === 0) {
            $path = substr($fullPath, strlen($scriptName));
        } else {
            $path = $fullPath;
        }

        // If path is empty, default to '/'
        if ($path === '' || $path === false) {
            $path = '/';
        }

        // Debug (optional while testing)
        // error_log("Method: $method, URI: $uri, FullPath: $fullPath, Script: $scriptName, Normalized: $path");

        if (!isset($this->routes[$method][$path])) {
            Response::json(['error' => 'Not found', 'path' => $path], 404);
        }

        $handler = $this->routes[$method][$path];

        if (is_array($handler) && count($handler) === 2) {
            [$class, $methodName] = $handler;
            $instance = new $class();
            $instance->{$methodName}();
            return;
        }

        if (is_callable($handler)) {
            $handler();
            return;
        }

        Response::json(['error' => 'Invalid route handler'], 500);
    }
}
