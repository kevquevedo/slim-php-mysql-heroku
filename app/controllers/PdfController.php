<?php

require_once './models/Pdf.php';

class PdfController extends Pdf {

    public function Down($request, $response, $args){
        try{   
            $pdf = new Pdf();
            $payload = json_encode(array("error" => 'No se pudo generar el archivo'));
            if($pdf->DescargaPDF()){
                $payload = json_encode(array("mensaje" => 'Se genero el archivo correctamente.'));
            }
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');
        }catch(Exception $ex){
            print "Error: " . $ex->getMessage();
            die();
        }
    }

}


?>