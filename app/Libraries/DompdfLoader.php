<?php
namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class DompdfLoader
{
    public function load()
    {
        // BORRA O COMENTA ESTA LÍNEA (Ya no es necesaria con Composer):
        // require_once FCPATH . 'dompdf/autoload.inc.php';

        // Configuración normal
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('chroot', FCPATH); // Recomendado para que lea imágenes de tu carpeta public

        return new Dompdf($options);
    }
}
