<?php
namespace App\Controllers;

use App\Models\ProductoModel;
use App\Libraries\DompdfLoader;

class InventarioController extends BaseController
{
    public function index()
    {
        $buscar = $this->request->getGet('buscar');

        $model = new ProductoModel();

        if ($buscar) {
            $productos = $model->like('nombre', $buscar)->findAll();
        } else {
            $productos = $model->findAll();
        }

        return view('inventario', [
            'productos' => $productos,
            'buscar' => $buscar,
        ]);
    }

    public function agregar()
    {
        helper(['form', 'url']);
        $imagen = $this->request->getFile('imagen');
        $imagenRuta = null;

        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            $nombreArchivo = $imagen->getRandomName();

            // Mover la imagen a writable/uploads
            $imagen->move(WRITEPATH . 'uploads', $nombreArchivo);

            // Copiar la imagen a public_html/uploads
            $origen = WRITEPATH . 'uploads/' . $nombreArchivo;
            $destino = FCPATH . 'uploads/' . $nombreArchivo;

            if (!is_dir(FCPATH . 'uploads')) {
                mkdir(FCPATH . 'uploads', 0755, true);
            }

            copy($origen, $destino);

            $imagenRuta = $nombreArchivo;
        }

        $productoModel = new ProductoModel();
        $productoModel->insert([
            'nombre' => $this->request->getPost('nombre'),
            'stock' => $this->request->getPost('stock'),
            'precio' => $this->request->getPost('precio'),
            'categoria' => $this->request->getPost('categoria'),
            'imagen' => $imagenRuta
        ]);

        session()->setFlashdata('success', 'Producto agregado correctamente.');
        return redirect()->to('/inventario');
    }

    public function eliminar($id)
    {
        $productoModel = new ProductoModel();
        $producto = $productoModel->find($id);

        // Borrar imagen física si existe
        if ($producto && $producto['imagen']) {
            $rutaImagen = WRITEPATH . 'uploads/' . $producto['imagen'];
            if (file_exists($rutaImagen)) {
                unlink($rutaImagen);
            }
            $rutaImagenPublic = FCPATH . 'uploads/' . $producto['imagen'];
            if (file_exists($rutaImagenPublic)) {
                unlink($rutaImagenPublic);
            }
        }

        if ($productoModel->delete($id)) {
            session()->setFlashdata('success', 'Producto eliminado correctamente.');
        } else {
            session()->setFlashdata('error', 'No se pudo eliminar el producto.');
        }
        return redirect()->to('/inventario');
    }

    public function mostrarImagen($nombreArchivo)
    {
        $ruta = WRITEPATH . 'uploads/' . $nombreArchivo;

        if (!file_exists($ruta)) {
            return $this->response->redirect(site_url('images/placeholder.png'));
        }

        $mime = mime_content_type($ruta);
        $imagenContenido = file_get_contents($ruta);

        return $this->response->setHeader('Content-Type', $mime)
                              ->setBody($imagenContenido);
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
        $imagenRuta = $producto['imagen'];

        if ($imagen && $imagen->isValid() && !$imagen->hasMoved()) {
            // Borrar imagen anterior en writable/uploads
            if ($imagenRuta && file_exists(WRITEPATH . 'uploads/' . $imagenRuta)) {
                unlink(WRITEPATH . 'uploads/' . $imagenRuta);
            }
            // Borrar imagen anterior en public_html/uploads
            if ($imagenRuta && file_exists(FCPATH . 'uploads/' . $imagenRuta)) {
                unlink(FCPATH . 'uploads/' . $imagenRuta);
            }

            $nombreArchivo = $imagen->getRandomName();

            // Mover la imagen a writable/uploads
            $imagen->move(WRITEPATH . 'uploads', $nombreArchivo);

            // Copiar la imagen a public_html/uploads
            $origen = WRITEPATH . 'uploads/' . $nombreArchivo;
            $destino = FCPATH . 'uploads/' . $nombreArchivo;

            if (!is_dir(FCPATH . 'uploads')) {
                mkdir(FCPATH . 'uploads', 0755, true);
            }

            copy($origen, $destino);

            $imagenRuta = $nombreArchivo;
        }

        $actualizado = $productoModel->update($id, [
            'nombre' => $this->request->getPost('nombre'),
            'stock' => $this->request->getPost('stock'),
            'precio' => $this->request->getPost('precio'),
            'categoria' => $this->request->getPost('categoria'),
            'imagen' => $imagenRuta
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
        return redirect()->to('/login');
    }
}
