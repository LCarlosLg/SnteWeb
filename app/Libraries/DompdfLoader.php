<?php
namespace App\Libraries;

require_once APPPATH . 'Libraries/dompdf/autoload.inc.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class DompdfLoader
{
    public function load()
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        return new Dompdf($options);
    }
}
