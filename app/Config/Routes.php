<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'UsuariosController::login');

// Rutas para Mostar el Login y el registro 
$routes->get('login', 'UsuariosController::login');
$routes->get('register', 'UsuariosController::register');
$routes->post('usuarios/attemptLogin', 'UsuariosController::attemptLogin');
$routes->post('usuarios/attemptRegister', 'UsuariosController::attemptRegister');

// Ruta para cerrar sesión
$routes->get('logout', 'InventarioController::logout');

// Rutas protegidas para clientes
$routes->group('', ['filter' => 'role:cliente'], function ($routes) {
    $routes->get('productos', 'ProductosController::index');
    // Ruta que inicia el pago
    $routes->get('checkout/stripe', 'CheckoutController::procesarPagoStripe');
    $routes->get('checkout/direccion', 'CheckoutController::direccion'); // Mostrar formulario
    $routes->post('checkout/guardarDireccion', 'CheckoutController::guardarDireccion'); // Procesar datos
    
    // Rutas de retorno de Stripe
    $routes->get('checkout/exito', 'CheckoutController::exito');
    $routes->get('checkout/cancelado', 'CheckoutController::cancelado');

    $routes->post('carrito/agregar', 'CarritoController::agregar');
    $routes->get('carrito', 'CarritoController::mostrar');
    $routes->get('carrito/datos', 'CarritoController::obtenerDatos');
    $routes->post('carrito/vaciar', 'CarritoController::vaciar');
    $routes->post('carrito/eliminar/(:num)', 'CarritoController::eliminarItem/$1');
    $routes->post('carrito/cantidad', 'CarritoController::cambiarCantidad');
});

// Rutas protegidas para empleados
$routes->group('', ['filter' => 'role:empleado'], function ($routes) {
    $routes->get('/inventario', 'InventarioController::index');
    $routes->post('/inventario/agregar', 'InventarioController::agregar');
    $routes->get('/inventario/eliminar/(:num)', 'InventarioController::eliminar/$1');
    $routes->post('inventario/actualizar/(:num)', 'InventarioController::actualizar/$1');
    $routes->get('/inventario/imagen/(:num)', 'InventarioController::obtenerImagen/$1');
    $routes->get('/inventario/reporte', 'InventarioController::descargarReporte');

    //Rutas para confirmacion de compras 
    $routes->get('checkout/direccion', 'CheckoutController::direccion');
    $routes->post('checkout/pago', 'CheckoutController::pago');
    $routes->post('checkout/completarPago', 'CheckoutController::completarPago');

    //Ruta para ver los pedidos en curso 
    $routes->get('pedidos', 'PedidosController::index', ['filter' => 'role:cliente']);


// ruta para ver imagenes 
$routes->get('inventario/mostrarImagen/(:segment)', 'InventarioController::mostrarImagen/$1');

});
