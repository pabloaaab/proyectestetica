<?php

use inquid\pdf\FPDF;
use app\models\Habeasdata;
use app\models\FormatoAutorizacion;
use app\models\Cliente;

class PDF extends FPDF {

    function Header() { 
	  //$this->SetFillColor(236, 236, 236);	  
	  $this->Image('images/logo.png', 12, 15, 25, 25);
	  
	  $this->Ln(8);
    }
    
    function Body($pdf,$model,$formato,$nombrecliente) {
        //contenido        
	$pdf->SetXY(10,50);
	$pdf->SetFont('Arial','B',10);	
	$pdf->MultiCell(0,5, utf8_decode($formato->formato) ,0,'J');
	$pdf->SetFont('Arial','B',9);
	//titulo
	$pdf->SetFont('Arial','B',20);
	$pdf->SetTextColor(042,046,075);
	$pdf->SetXY(10, 42);
	$pdf->Cell(10, 5, utf8_decode("HABEAS DATA - AUTORIZACIÓN"), 0, 0, 'J');
	//firma 1
	$pdf->SetTextColor(0,0,0);
	$pdf->SetFont('Arial','B',9);
	$pdf->SetXY(10, 200);
	$pdf->Cell(10, 5, "___________________________________", 0, 0, 'J');	
	$pdf->SetXY(10, 210); //paciente
	$pdf->Cell(10, 5, utf8_decode('Nombre: '.$nombrecliente->nombre1.' '.$nombrecliente->nombre2.' '.$nombrecliente->apellido1.' '.$nombrecliente->apellido2), 0, 0, 'J');
	$pdf->SetXY(10, 220); //identificacion
	$pdf->Cell(10, 5, utf8_decode('Identificacion: '.$model->identificacion), 0, 0, 'J');
	$pdf->SetXY(10, 230); //fecha
	$pdf->Cell(10, 5, utf8_decode('Fecha Autorizacion: '.$model->fechaautorizacion), 0, 0, 'J');
	
	$rutafirma = "firmaCliente/".$model->firma; //ruta firma cliente	
	$pdf->Image($rutafirma,15, 185, 50, 15);
    }

    function Footer() 
    { 
      $this->Text(180, 275, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');	  
    } 
	
} 
$nombrecliente = Cliente::find()->where(['=','identificacion',$model->identificacion])->one();
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model,$formato,$nombrecliente);
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 10);
$pdf->Output("Habeasdata.pdf", 'D');

exit;
		
?>
<?php 
	function mesespanol($mes1)
	{
		if ($mes1 == "01"){
			$mes2 = "Enero";
		}
		if ($mes1 == "02"){
			$mes2 = "Febrero";
		}
		if ($mes1 == "03"){
			$mes2 = "Marzo";
		}
		if ($mes1 == "04"){
			$mes2 = "Abril";
		}
		if ($mes1 == "05"){
			$mes2 = "Mayo";
		}
		if ($mes1 == "06"){
			$mes2 = "Junio";
		}
		if ($mes1 == "07"){
			$mes2 = "Julio";
		}
		if ($mes1 == "08"){
			$mes2 = "Agosto";
		}
		if ($mes1 == "09"){
			$mes2 = "Septiembre";
		}
		if ($mes1 == "10"){
			$mes2 = "Octubre";
		}
		if ($mes1 == "11"){
			$mes2 = "Noviembre";
		}
		if ($mes1 == "12"){
			$mes2 = "Diciembre";
		}
	return ($mes2);
	}
?>