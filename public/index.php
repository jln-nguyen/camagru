<?php

require_once __DIR__ . '/../srcs/Core/Autoloader.php';
Autoloader::register();

$router = new Router();
$router->addRoute('GET', '/register', [AuthController::class, 'showRegisterForm']);
$router->addRoute('POST', '/register', [AuthController::class, 'register']);

$router->dispatch();
?>