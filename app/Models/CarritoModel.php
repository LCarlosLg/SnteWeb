<?php

namespace App\Models;

use CodeIgniter\Model;

class CarritoModel extends Model
{
    protected $table = 'carrito';
    protected $primaryKey = 'id';
    protected $allowedFields = ['usuario_id', 'producto_id', 'cantidad', 'fecha_agregado'];
    protected $useTimestamps = false;

    public function obtenerCarritoPorUsuario($usuarioId)
    {
        return $this->select('carrito.*, productos.nombre, productos.precio')
                    ->join('productos', 'productos.id = carrito.producto_id')
                    ->where('usuario_id', $usuarioId)
                    ->findAll();
    }

    public function agregarProducto($usuarioId, $productoId)
    {
        $registro = $this->where('usuario_id', $usuarioId)
                        ->where('producto_id', $productoId)
                        ->first();

        if ($registro) {
            // Si ya existe, aumenta cantidad
            $this->update($registro['id'], ['cantidad' => $registro['cantidad'] + 1]);
        } else {
            // Si no existe, inserta nuevo
            $this->insert([
                'usuario_id' => $usuarioId,
                'producto_id' => $productoId,
                'cantidad' => 1
            ]);
        }
    }
}
