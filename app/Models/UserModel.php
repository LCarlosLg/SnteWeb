<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'usuarios';  // Nombre de la tabla de la base de datos
    protected $primaryKey = 'id';   // Llave primaria
    protected $allowedFields = ['nombres', 'apellidos', 'email', 'telefono', 'password', 'role'];  // Campos permitidos
}

