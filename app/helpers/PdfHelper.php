<?php
// Simple PDF export using dompdf
require_once __DIR__ . '/../../vendor/autoload.php';
use Dompdf\Dompdf;

class PdfHelper {
    public static function render($html, $filename = 'documento.pdf') {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ["Attachment" => true]);
        exit;
    }
}
