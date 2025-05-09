<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Verifica si el usuario está logueado
        if (!$session->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión primero');
        }

        // Si se especificó un rol requerido
        if ($arguments && !in_array($session->get('role'), $arguments)) {
            return redirect()->to('/')->with('error', 'No tienes permiso para acceder a esta sección');
        }

        return null; // continuar
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {}
}
