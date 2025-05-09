<?php
namespace App\Controllers;

use App\Models\ProductoModel;
use CodeIgniter\HTTP\RequestInterface;

class InventarioController extends BaseController
{
    public function index()
    {
        $productoModel = new ProductoModel();
        $data['productos'] = $productoModel->findAll();
        return view('inventario', $data);
    }

    public function agregar()
    {
        helper(['form', 'url']);

        $imagen = $this->request->getFile('imagen');
        $imagenBlob = null;

        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            $imagenBlob = file_get_contents($imagen->getTempName());
        }

        $productoModel = new ProductoModel();
        $productoModel->insert([
            'nombre' => $this->request->getPost('nombre'),
            'stock' => $this->request->getPost('stock'),
            'precio' => $this->request->getPost('precio'),
            'categoria' => $this->request->getPost('categoria'),
            'imagen' => $imagenBlob
        ]);

        return redirect()->to('/inventario');
    }

    public function eliminar($id)
    {
        $productoModel = new ProductoModel();
        $productoModel->delete($id);
        return redirect()->to('/inventario');
    }

    public function obtenerImagen($id)
    {
        $productoModel = new ProductoModel();
        $producto = $productoModel->find($id);

        if ($producto && $producto['imagen']) {
            return $this->response
                        ->setHeader('Content-Type', 'image/jpeg')
                        ->setBody($producto['imagen']);
        }

        // Imagen de respaldo si no hay
        return redirect()->to(base_url('images/placeholder.png'));
    }
}
