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

// Rutas protegidas para clientes
$routes->group('', ['filter' => 'role:cliente'], function ($routes) {
    $routes->get('productos', 'ProductosController::index');
});

// Rutas protegidas para empleados
$routes->group('', ['filter' => 'role:empleado'], function ($routes) {
    $routes->get('/inventario', 'InventarioController::index');
    $routes->post('/inventario/agregar', 'InventarioController::agregar');
    $routes->get('/inventario/eliminar/(:num)', 'InventarioController::eliminar/$1');
    $routes->get('/inventario/imagen/(:num)', 'InventarioController::obtenerImagen/$1');
});

