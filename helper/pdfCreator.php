<php
 require_once 'dompdf/autoload.inc.php';

 use Dompdf\Dompdf;

 class Pdf creator{

 public function create($html){
    $dompdf= new Dompdf();
    $dompdf=loadHtml($html);
    $dompdf->setPaper('A4',"portrait");
    $dompdf->render();

    $dompdf->stream("nuevo_file_pdf",['Attachment'=>0]);
 }
 }
