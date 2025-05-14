<?php
namespace App\Controllers;

use App\Models\ProductoModel;
use App\Libraries\DompdfLoader;

class InventarioController extends BaseController
{
    public function index()
    {
        $productoModel = new ProductoModel();
        $data['productos'] = $productoModel->findAll();

        $data['success'] = session()->getFlashdata('success');
        $data['error'] = session()->getFlashdata('error');

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

        session()->setFlashdata('success', 'Producto agregado correctamente.');
        return redirect()->to('/inventario');
    }

    public function eliminar($id)
    {
        $productoModel = new ProductoModel();
        if ($productoModel->delete($id)) {
            session()->setFlashdata('success', 'Producto eliminado correctamente.');
        } else {
            session()->setFlashdata('error', 'No se pudo eliminar el producto.');
        }
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

        return redirect()->to(base_url('images/placeholder.png'));
    }

    public function actualizar($id)
    {
        helper(['form', 'url']);
        $productoModel = new ProductoModel();
        $producto = $productoModel->find($id);

        if (!$producto) {
            session()->setFlashdata('error', 'Producto no encontrado.');
            return redirect()->to('/inventario');
        }

        $imagen = $this->request->getFile('imagen');
        $imagenBlob = $producto['imagen'];

        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            $imagenBlob = file_get_contents($imagen->getTempName());
        }

        $actualizado = $productoModel->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'stock' => $this->request->getPost('stock'),
            'precio' => $this->request->getPost('precio'),
            'categoria' => $this->request->getPost('categoria'),
            'imagen' => $imagenBlob
        ]);

        if ($actualizado) {
            session()->setFlashdata('success', 'Producto actualizado correctamente.');
        } else {
            session()->setFlashdata('error', 'No se pudo actualizar el producto.');
        }

        return redirect()->to('/inventario');
    }

    public function descargarReporte()
    {
        $productoModel = new ProductoModel();
        $productos = $productoModel->findAll();

        $html = view('reporte_pdf', ['productos' => $productos]);

        $dompdfLoader = new DompdfLoader();
        $dompdf = $dompdfLoader->load();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $this->response
                    ->setHeader('Content-Type', 'application/pdf')
                    ->setHeader('Content-Disposition', 'attachment; filename="reporte_inventario.pdf"')
                    ->setBody($dompdf->output());
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login'); // Asegúrate de que esta ruta esté configurada y tenga su vista
    }
}
