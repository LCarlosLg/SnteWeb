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
        
        // CORRECCIÓN: Usamos 'user_id' que es como se guardó en el Login
        $usuarioId = $session->get('user_id'); 
        $productoId = $this->request->getPost('producto_id');

        // Validación: Si no encuentra el ID en la sesión, devuelve error
        if (!$usuarioId) {
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(401)->setJSON(['error' => 'No se encontró la sesión del usuario.']);
            }
            return redirect()->to('/login');
        }

        $carritoModel = new CarritoModel();
        
        try {
            // Nota: Aquí pasamos el ID recuperado ($usuarioId) a la columna 'usuario_id' de la BD
            $carritoModel->agregarProducto($usuarioId, $productoId);
            
            // Respuesta exitosa para AJAX
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Producto agregado']);
            }
            
            return redirect()->back()->with('mensaje', 'Producto agregado al carrito');

        } catch (\Exception $e) {
            // Manejo de errores
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Error en servidor: ' . $e->getMessage()]);
            }
            return redirect()->back()->with('error', 'No se pudo agregar el producto');
        }
    }

    public function mostrar()
    {
        $session = session();
        
        // Usamos la clave correcta 'user_id'
        $usuarioId = $session->get('user_id'); 

        // Si no hay sesión, mandamos al login
        if (!$usuarioId) {
            return redirect()->to('/login');
        }

        $carritoModel = new CarritoModel();
        // Obtenemos los productos reales
        $productos = $carritoModel->obtenerCarritoPorUsuario($usuarioId);

        // Enviamos los datos a la vista
        return view('carrito', ['productos' => $productos]);
    }

    // Función para alimentar el MODAL con datos frescos
    public function obtenerDatos()
    {
        $session = session();
        $usuarioId = $session->get('user_id'); // Usamos la clave correcta

        if (!$usuarioId) {
            return $this->response->setJSON(['autenticado' => false]);
        }

        $carritoModel = new CarritoModel();
        $productos = $carritoModel->obtenerCarritoPorUsuario($usuarioId);

        // Devolvemos los productos en formato JSON
        return $this->response->setJSON([
            'autenticado' => true,
            'productos' => $productos
        ]);
    }
    
    public function vaciar()
    {
        $session = session();
        $usuarioId = $session->get('user_id');
        
        if ($usuarioId) {
            $carritoModel = new CarritoModel();
            // Borramos todo lo que coincida con este usuario
            $carritoModel->where('usuario_id', $usuarioId)->delete();
        }
        
        return $this->response->setJSON(['status' => 'success']);
    }

    public function eliminarItem($productoId)
    {
        $session = session();
        $usuarioId = $session->get('user_id');

        if ($usuarioId) {
            $carritoModel = new CarritoModel();
            // Borramos solo el producto específico de este usuario
            $carritoModel->where('usuario_id', $usuarioId)
                         ->where('producto_id', $productoId)
                         ->delete();
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function cambiarCantidad()
    {
        $session = session();
        $usuarioId = $session->get('user_id');
        $productoId = $this->request->getPost('producto_id');
        $accion = $this->request->getPost('accion'); // 'sumar' o 'restar'

        if (!$usuarioId) return $this->response->setJSON(['status' => 'error']);

        $carritoModel = new CarritoModel();
        
        // Buscamos el ítem actual
        $item = $carritoModel->where('usuario_id', $usuarioId)
                             ->where('producto_id', $productoId)
                             ->first();

        if ($item) {
            $nuevaCantidad = $item['cantidad'];
            
            if ($accion === 'sumar') {
                $nuevaCantidad++;
            } elseif ($accion === 'restar') {
                $nuevaCantidad--;
            }

            if ($nuevaCantidad > 0) {
                $carritoModel->update($item['id'], ['cantidad' => $nuevaCantidad]);
            } else {
                // Si baja a 0, lo eliminamos
                $carritoModel->delete($item['id']);
            }
        }

        return $this->response->setJSON(['status' => 'success']);
    }
}
