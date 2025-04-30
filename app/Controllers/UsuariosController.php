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

        // Obtener los datos del formulario
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Buscar al usuario por su correo
        $user = $userModel->where('email', $email)->first();

    
        // Verificar las credenciales
        if ($user && password_verify($password, $user['password'])) {

            // Guardar los datos del usuario en la sesión
            $session->set([
                'user_id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'logged_in' => true
            ]);

            // Redirigir según el rol del usuario
            return redirect()->to($user['role'] === 'cliente' ? '/productos' : '/reportes');
        } else {
            print_r($user);
            var_dump(  password_verify($user['password'], $password));
            // Mostrar mensaje de error si las credenciales son incorrectas
            return redirect()->back()->with('error', 'Credenciales incorrectas');
        }
    }

    public function attemptRegister()
    {
        $userModel = new UserModel();

        // Obtener los datos del formulario
        $nombres = $this->request->getPost('nombres');
        $apellidos = $this->request->getPost('apellidos');
        $telefono = $this->request->getPost('telefono');
        $email = $this->request->getPost('email'); // Obtener correo
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('tipo_usuario');

        // Verificar si el correo ya está registrado
        $existingUser = $userModel->where('email', $email)->first();
        if ($existingUser) {
            return redirect()->back()->with('error', 'El correo electrónico ya está registrado');
        }

        // Registrar el nuevo usuario
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        
        $userModel->save([
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'telefono' => $telefono,
            'email' => $email,  // Guardar correo en la base de datos
            'password' => $hashedPassword,
            'role' => $role
        ]);

    

        // Redirigir al login después de un registro exitoso
        return redirect()->to('login')->with('success', 'Registro exitoso. Ahora puedes iniciar sesión');
    }
}
