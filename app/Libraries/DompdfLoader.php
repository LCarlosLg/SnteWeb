<?php
namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class DompdfLoader
{
    public function load()
    {
        require_once FCPATH . 'dompdf/autoload.inc.php';

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        return new Dompdf($options);
    }
}
