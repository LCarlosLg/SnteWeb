<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PedidoModel;

class PedidosController extends Controller
{
    public function index()
    {
        $session = session();
        $usuarioId = $session->get('user_id');

        $pedidoModel = new PedidoModel();
        $pedidos = $pedidoModel->where('usuario_id', $usuarioId)
                            ->where('estado', 'En curso')
                            ->findAll();

        return view('pedidos_en_curso', ['pedidos' => $pedidos]);
    }
}
