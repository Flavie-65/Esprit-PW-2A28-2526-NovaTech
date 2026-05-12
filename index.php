<?php

declare(strict_types=1);

/**
 * Front controller: dispatches ?module=&action= to Controller::method().
 * Defaults: module=home, action=index
 */

$baseDir = __DIR__;

spl_autoload_register(static function (string $class) use ($baseDir): void {
    $paths = [
        $baseDir . '/controllers/' . $class . '.php',
        $baseDir . '/models/entities/' . $class . '.php',
        $baseDir . '/models/managers/' . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (is_file($path)) {
            require_once $path;
            return;
        }
    }
});

$module = isset($_GET['module']) ? (string) $_GET['module'] : 'home';
$action = isset($_GET['action']) ? (string) $_GET['action'] : 'index';

$module = strtolower(preg_replace('/[^a-z0-9_]/', '', $module)) ?: 'home';
$action = strtolower(preg_replace('/[^a-z0-9_]/', '', $action)) ?: 'index';

$parts = array_filter(explode('_', $module), static fn (string $p): bool => $p !== '');
$pascal = '';
foreach ($parts as $part) {
    $pascal .= ucfirst($part);
}
$controllerClass = ($pascal !== '' ? $pascal : 'Home') . 'Controller';

if (!class_exists($controllerClass)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Module not found.';
    exit;
}

$controller = new $controllerClass();

if (!method_exists($controller, $action)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=UTF-8');
    echo 'Action not found.';
    exit;
}

$controller->{$action}();
