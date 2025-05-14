<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'usuarios';  // Nombre de la tabla
    protected $primaryKey = 'id';   // Clave primaria
    protected $allowedFields = ['nombres', 'apellidos', 'email', 'telefono', 'password', 'role'];  // Campos permitidos
}

