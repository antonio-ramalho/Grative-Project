<?php

require_once __DIR__ . '/../../vendor/autoload.php';

$config = require __DIR__ . '/../config/database.php';

$pdo = new PDO($config['connection'] . ':' . $config['database']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$router = new App\Routes\Router($pdo);
$controller = new App\Controllers\PostController($pdo);

$router->get('/', [$controller, 'index']);
$router->get('/post/{id}', function($id) use ($controller) {
    $controller->show($id);
});
$router->post('/comment', [$controller, 'storeComment']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);