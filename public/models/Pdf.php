<?php

use Fpdf\Fpdf;

class Pdf
{
    
    public function DescargaPDF()
    {
        $retorno = false;

        $pdf = new FPDF();
        $pdf->AddPage(); //Agrega una pagina nueva
        $pdf->SetFont('Arial', 'B', 25);
        $pdf->Cell(0, 15, 'TRABAJO PRACTICO - LA COMANDA', 1, 3, 'L'); //TITULO
        $pdf->Ln(3);

        $pdf->SetFont('Arial', '', 15);
        $pdf->Cell(0, 4, 'Nombre: Kevin Quevedo', 0, 1, 'L');
        $pdf->Cell(20, 0, '', 'T');//Subrayado
        $pdf->Ln(3);
        
        $pdf->Cell(0, 4, 'Email: quevedo.kevin1994@gmail.com', 0, 1, 'L');
        $pdf->Cell(15, 0, '', 'T'); //Subrayado
        $pdf->Ln(5);
        
        $pdf->Image('img/restaurant.jpg',30,50,150,150,'jpg');

        $pdf->Output('F', './pdfs/' . 'LogoDeLaComanda' .'.pdf', 'I');    
        $retorno = true;

        return $retorno;
    }

    
}

?>