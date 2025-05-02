<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'UsuariosController::login');

// Rutas para el controlador UsuariosController
$routes->get('login', 'UsuariosController::login');
$routes->get('register', 'UsuariosController::register');
$routes->post('usuarios/attemptLogin', 'UsuariosController::attemptLogin');
$routes->post('usuarios/attemptRegister', 'UsuariosController::attemptRegister');
// Vista de los productos
$routes->get('productos', 'ProductosController::index');




