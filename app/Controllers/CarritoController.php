<?php

namespace App\Controllers;

use App\Models\CarritoModel;
use CodeIgniter\Controller;

// Este controllador tiene como funcion agregar y mostrar los productos agregados en el carrito de compras de nuestra web

class CarritoController extends Controller
{
    public function agregar()
    {
        $session = session();
        $usuarioId = $session->get('usuario_id');
        $productoId = $this->request->getPost('producto_id');

        $carritoModel = new CarritoModel();
        $carritoModel->agregarProducto($usuarioId, $productoId);

        return redirect()->back()->with('mensaje', 'Producto agregado al carrito');
    }

    public function mostrar()
    {
        $session = session();
        $usuarioId = $session->get('usuario_id');

        $carritoModel = new CarritoModel();
        $productos = $carritoModel->obtenerCarritoPorUsuario($usuarioId);

        return view('carrito', ['productos' => $productos]);
    }
}
