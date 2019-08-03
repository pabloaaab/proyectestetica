<?php

use inquid\pdf\FPDF;
use app\models\Facturaventa;
use app\models\Facturaventadetalle;
use app\models\Matriculaempresa;
use app\models\Municipio;
use app\models\Departamento;

class PDF extends FPDF {

    function Header() {
        //$this->SetFillColor(236, 236, 236);	
        $this->Image('images/logo.png', 17, 15, 24, 24);
        //$this->Image('images/firma_ever.PNG', 10, 10, 45, 35);
        $this->SetFont('Helvetica', 'B', 14);
        $this->SetXY(5, 10);
        $this->Cell(203, 266, '', 1, 0, 'C');
        $this->SetXY(10, 15);
        $this->Cell(40, 24, '', 1, 0, 'C');
        $this->SetXY(50, 15);
        $this->Cell(155, 16, 'F- CONDICIONES GENERALES', 1, 0, 'C');
        $this->SetFont('Helvetica', 'B', 12);
        $this->SetXY(50, 31);
        $this->Cell(52, 8, utf8_decode('Versión: 2'), 1, 0, 'C');
        $this->Cell(52, 8, utf8_decode('Página: ') . $this->PageNo() . ' de {nb}', 1, 0, 'C');
        $this->Cell(51, 8, utf8_decode('Código: F-ADR-03'), 1, 0, 'C');
        $this->Ln(6);        
    }

    function EncabezadoDetalles() {
        
    }

    function Body($pdf,$model,$formato) {
        //Contenido
	$pdf->SetXY(10, 46);
	$pdf->SetFont('Arial','B',10);	
	$pdf->MultiCell(195,4.5, utf8_decode($formato->formato) ,0,'J');
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY(155, 176); //ciudad firma
	$pdf->Cell(40, 5, utf8_decode($model->ciudad_firma), 0, 0, 'C');
	$pdf->SetFont('Arial','B',10);
	setlocale(LC_ALL,"es_ES@euro","es_ES","esp");
	$d = $model->fecha_autoriza;
	$fecha = strtoupper(strftime(" A LOS %d DIAS DEL MES DE %B DE %Y", strtotime($d)));
	$pdf->SetXY(10, 186);//fecha firma
	$pdf->Cell(192, 5, utf8_decode($fecha), 0, 0, 'C');
	$pdf->SetXY(43, 218);//cedula estudiante
	$pdf->Cell(35, 5, utf8_decode($model->identificacion), 0, 0, 'L');	
	$rutafirma ="firmaEstudiante/".$model->firma; //ruta firma estudiante
	$rutafirmaac ="firmaAcudiente/".$model->firmaacudiente; //ruta firma acudiente
	$pdf->Image($rutafirma,30, 192, 50, 15);
	if ($model->firmaacudiente != ""){
		$pdf->Image($rutafirmaac,129, 192, 50, 15);
	}
	
	$pdf->Image('firmaEstudiante/firma_ever.PNG',92, 215, 35, 18);
    }

    function Footer() {

        
    }

}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model,$formato);
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 10);
$pdf->Output("Autorizacion.pdf", 'D');

exit;