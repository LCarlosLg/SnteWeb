<?php

namespace App\Controllers;

use App\Models\CarritoModel;
use App\Models\PedidoModel;
use App\Models\DetallePedidoModel;
use CodeIgniter\Controller;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class CheckoutController extends Controller
{
    public function direccion()
    {
        return view('direccion');
    }

    public function guardarDireccion()
    {
        $session = session();

        // Recibimos los datos del formulario con los nombres exactos de la vista
        $direccion = [
            'calle'           => $this->request->getPost('calle'),
            'cp'              => $this->request->getPost('cp'),
            'numero_exterior' => $this->request->getPost('numero_exterior'),
            'numero_interior' => $this->request->getPost('numero_interior'),
            'colonia'         => $this->request->getPost('colonia'),
            'ciudad'          => $this->request->getPost('ciudad'),
            'referencias'     => $this->request->getPost('referencias'),
        ];

        // Guardamos en sesión
        $session->set('direccion', $direccion);

        // Redirigimos a Stripe
        return redirect()->to(base_url('checkout/stripe'));
    }

    public function procesarPagoStripe()
    {
        $session = session();
        $usuarioId = $session->get('user_id');
        
        // 1. Obtener productos del carrito
        $carritoModel = new CarritoModel();
        $itemsCarrito = $carritoModel->obtenerCarritoPorUsuario($usuarioId);

        if (empty($itemsCarrito)) {
            return redirect()->to('/carrito');
        }

        // 2. Configurar Stripe con tu clave secreta del .env
        Stripe::setApiKey(getenv('STRIPE_SECRET'));

        // 3. Formatear los productos para Stripe (line_items)
        // Basado en la documentación que mandaste: line_items.data.price_data
        $lineItems = [];
        foreach ($itemsCarrito as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'mxn', // Moneda (pesos mexicanos)
                    'product_data' => [
                        'name' => $item['nombre'],
                        // Opcional: 'images' => [base_url('uploads/' . $item['imagen'])],
                    ],
                    // IMPORTANTE: Stripe usa centavos. Multiplica por 100.
                    'unit_amount' => (int)($item['precio'] * 100), 
                ],
                'quantity' => (int)$item['cantidad'],
            ];
        }

        // 4. Crear la Sesión de Checkout
        try {
            $checkoutSession = Session::create([
                'payment_method_types' => ['card'], // Acepta tarjetas
                'line_items' => $lineItems,
                'mode' => 'payment', // Pago único
                
                // URLs a donde volverá el usuario
                'success_url' => base_url('checkout/exito') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url'  => base_url('checkout/cancelado'),
                
                // Metadatos para identificar al usuario cuando regrese
                'client_reference_id' => $usuarioId,
                'customer_email' => $session->get('email'), // Si tienes el email en sesión
            ]);

            // 5. Redirigir al usuario a la página de Stripe
            return redirect()->to($checkoutSession->url);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al conectar con Stripe: ' . $e->getMessage());
        }
    }

    // Esta función se ejecuta cuando el pago fue ÉXITOSO en Stripe
    public function exito()
    {
        $session = session();
        $sessionId = $this->request->getGet('session_id'); // ID que nos devuelve Stripe

        // Aquí deberías llamar a tu lógica de "completarPago" que tenías antes
        // Pero asegurándote que no se dupliquen pedidos.
        
        // ... Lógica para guardar en PedidoModel y DetallePedidoModel ...
        // ... Enviar correo ...
        // ... Vaciar carrito ...

        // Ejemplo rápido reutilizando tu lógica anterior:
        return $this->completarPagoInterno(); 
    }

    public function cancelado()
    {
        return redirect()->to('/carrito')->with('error', 'El pago fue cancelado.');
    }

    // Mueve tu lógica de guardar en BD aquí para llamarla desde exito()
    private function completarPagoInterno() {
        $session = session();
        $carritoModel = new CarritoModel();

        $usuarioId = $session->get('user_id');
        $correoUsuario = $session->get('email');
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

    public function pago()
    {
        return view('checkout_pago');
    }

    public function completarPago()
    {
        $session = session();
        $carritoModel = new CarritoModel();

        $usuarioId = $session->get('user_id');
        $correoUsuario = $session->get('email');
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
