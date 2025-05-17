<?php

namespace App\Controllers;

use App\Models\CarritoModel;
use App\Models\PedidoModel;
use App\Models\DetallePedidoModel;
use CodeIgniter\Controller;
use CodeIgniter\Email\Email;

class CheckoutController extends Controller
{
    public function direccion()
    {
        return view('checkout_direccion');
    }

    public function guardarDireccion()
    {
        $session = session();

        $direccion = [
            'nombre' => $this->request->getPost('nombre'),
            'apellidos' => $this->request->getPost('apellidos'),
            'telefono' => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion'),
            'codigo_postal' => $this->request->getPost('codigo_postal'),
            'estado' => $this->request->getPost('estado'),
            'ciudad' => $this->request->getPost('ciudad'),
        ];

        $session->set('direccion', $direccion);

        return redirect()->to(base_url('checkout/pago'));
    }

    public function pago()
    {
        return view('checkout_pago');
    }

    public function completarPago()
    {
        $session = session();
        $carritoModel = new CarritoModel();

        $usuarioId = $session->get('usuario_id');
        $correoUsuario = $session->get('correo');
        $direccion = $session->get('direccion');
        $productos = $carritoModel->obtenerCarritoPorUsuario($usuarioId);

        // 1. Insertar pedido
        $pedidoModel = new PedidoModel();
        $pedidoId = $pedidoModel->insert([
            'usuario_id' => $usuarioId,
            'direccion' => json_encode($direccion),
            'estado' => 'En curso',
        ]);

        // 2. Insertar detalle de productos
        $detalleModel = new DetallePedidoModel();
        foreach ($productos as $p) {
            $detalleModel->insert([
                'pedido_id' => $pedidoId,
                'producto_id' => $p['producto_id'],
                'cantidad' => $p['cantidad'],
                'precio' => $p['precio'],
            ]);
        }

        // 3. Enviar correo al usuario
        $email = \Config\Services::email();
        $email->setTo($correoUsuario);
        $email->setFrom('tuemail@ejemplo.com', 'Tienda en Línea');

        $email->setSubject('Confirmación de compra');
        $mensaje = "<h3>Gracias por tu compra</h3>";
        $mensaje .= "<p>Tu pedido está en curso. Aquí están los detalles:</p>";
        $mensaje .= "<h4>Dirección de envío:</h4>";
        foreach ($direccion as $k => $v) {
            $mensaje .= "<strong>" . ucfirst(str_replace('_', ' ', $k)) . ":</strong> $v<br>";
        }
        $mensaje .= "<h4>Productos:</h4><ul>";
        $total = 0;
        foreach ($productos as $p) {
            $subtotal = $p['precio'] * $p['cantidad'];
            $mensaje .= "<li>{$p['nombre']} x{$p['cantidad']} - $" . number_format($subtotal, 2) . "</li>";
            $total += $subtotal;
        }
        $mensaje .= "</ul><p><strong>Total:</strong> $" . number_format($total, 2) . "</p>";

        $email->setMessage($mensaje);
        $email->setMailType('html');
        $email->send();

        // 4. Limpiar carrito y sesión
        $carritoModel->where('usuario_id', $usuarioId)->delete();
        $session->remove('direccion');

        return view('confirmacion');
    }
}
