<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class UsuariosController extends Controller
{
    public function login()
    {
        return view('auth/login');
    }

    public function register()
    {
        return view('auth/register');
    }

    public function attemptLogin()
    {
        $session = session();
        $userModel = new UserModel();

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password'])) {
            $session->set([
                'user_id'   => $user['id'],
                'email'     => $user['email'],
                'role'      => $user['role'],
                'logged_in' => true
            ]);

            // Redirigir según rol
            if ($user['role'] === 'cliente') {
                return redirect()->to('/productos');
            } elseif ($user['role'] === 'empleado') {
                return redirect()->to('/inventario');
            } else {
                return redirect()->to('/')->with('error', 'Rol de usuario no válido.');
            }
        } else {
            return redirect()->back()->with('error', 'Credenciales incorrectas');
        }
    }

    public function attemptRegister()
    {
        $userModel = new UserModel();

        $nombres   = $this->request->getPost('nombres');
        $apellidos = $this->request->getPost('apellidos');
        $telefono  = $this->request->getPost('telefono');
        $email     = $this->request->getPost('email');
        $password  = $this->request->getPost('password');
        $role      = $this->request->getPost('tipo_usuario');

        // Verificar si ya existe
        if ($userModel->where('email', $email)->first()) {
            return redirect()->back()->with('error', 'El correo electrónico ya está registrado');
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $userModel->save([
            'nombres'   => $nombres,
            'apellidos' => $apellidos,
            'telefono'  => $telefono,
            'email'     => $email,
            'password'  => $hashedPassword,
            'role'      => $role
        ]);

        return redirect()->to('login')->with('success', 'Registro exitoso. Ahora puedes iniciar sesión');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
