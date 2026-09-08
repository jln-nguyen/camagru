<?php

session_start();

require_once __DIR__ . '/../srcs/Core/Autoloader.php';
Autoloader::register();

$router = new Router();

$router->addRoute('GET', '/', [GalleryController::class, 'index']);
$router->addRoute('GET', '/gallery/{id}', [GalleryController::class, 'show']);
$router->addRoute('POST', '/like/{id}', [GalleryController::class, 'like']);
$router->addRoute('POST', '/comment/{id}', [GalleryController::class, 'comment']);

$router->addRoute('GET', '/register', [AuthController::class, 'showRegisterForm']);
$router->addRoute('POST', '/register', [AuthController::class, 'register']);
$router->addRoute('GET', '/confirm', [AuthController::class, 'confirmRegistration']);
$router->addRoute('GET', '/login', [AuthController::class, 'showLoginForm']);
$router->addRoute('POST', '/login', [AuthController::class, 'login']);
$router->addRoute('GET', '/logout', [AuthController::class, 'logout']);
$router->addRoute('GET', '/forgot-password', [ProfileController::class, 'showForgotPasswordForm']);
$router->addRoute('POST', '/forgot-password', [ProfileController::class, 'forgotPassword']);
$router->addRoute('GET', '/reset-password', [ProfileController::class, 'showResetPasswordForm']);
$router->addRoute('POST', '/reset-password', [ProfileController::class, 'resetPassword']);
$router->addRoute('GET', '/confirm-reset-password', [ProfileController::class, 'confirmResetPassword']);

$router->addRoute('GET', '/profile', [ProfileController::class, 'showProfile']);
$router->addRoute('GET', '/modify', [ProfileController::class, 'showModifyProfileForm']);
$router->addRoute('POST', '/modify', [ProfileController::class, 'modifyProfile']);

$router->addRoute('GET', '/edit-images', [EditController::class, 'showEditImagesPage']);
$router->addRoute('POST', '/delete-image/{id}', [EditController::class, 'deleteImage']);

$router->dispatch();
?>