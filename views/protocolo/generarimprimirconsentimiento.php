<?php

use inquid\pdf\FPDF;
use app\models\Habeasdata;
use app\models\FormatoAutorizacion;
use app\models\Cliente;

class PDF extends FPDF {

    function Header() {
        $consentimiento_cliente_pk = $GLOBALS['consentimiento_cliente_pk'];
        $consentimiento_cliente = app\models\ConsentimientoCliente::find()->where(['=','consentimiento_cliente_pk',$consentimiento_cliente_pk])->one();        
        $formato = app\models\Consentimiento::findOne(1);
        $this->Image('images/logo2.png', 140, 15, 80, 17);
        //$this->SetFont('Arial','B',9);
        
	/*$this->SetXY(40, 71); //identificacion		
	$this->Cell(10, 5, utf8_decode($consentimiento_cliente->identificacion), 0, 0, 'J');
	
        */
        $this->Ln(38);
    }
    
    
    
    function Body($pdf,$model,$formato) {        
        //Contenido        
        $cliente = Cliente::find()->where(['=','identificacion',$model->identificacion])->one();
	$pdf->SetXY(10,35);
	$pdf->SetFont('Arial','B',10);	
	$pdf->MultiCell(0,5.33, utf8_decode($formato->formato) ,0,'J');
        $pdf->SetXY(45, 67); //identificacion		
	$pdf->Cell(10, 5, utf8_decode($model->identificacion), 0, 0, 'J');
        $pdf->SetXY(27, 72); //fecha
	$pdf->Cell(10, 5, utf8_decode($model->fechaconsentimiento), 0, 0, 'J');
        $pdf->SetXY(108, 264); //Sede
	$pdf->Cell(10, 5, utf8_decode($model->sedeFk->sede), 0, 0, 'J');
        $pdf->SetXY(148, 264); //dia de la firma
	$diafecha = substr($model->fechaconsentimiento,8,2);
	$pdf->Cell(10, 5, utf8_decode($diafecha), 0, 0, 'J');
        $pdf->SetXY(181, 264); //mes de la firma
	$mes1 = substr($model->fechaconsentimiento,5,2);
	$mesfecha = mesespanol($mes1);
	$pdf->Cell(19, 5, utf8_decode($mesfecha), 0, 0, 'C');
        $pdf->SetXY(15, 269.4); //año de la firma
	$aniofecha = substr($model->fechaconsentimiento,2,2);
	$pdf->Cell(19, 5, utf8_decode($aniofecha), 0, 0, 'C');        
	//firma 1
	$pdf->SetXY(10, 300);
	$pdf->Cell(10, 5, "___________________________________", 0, 0, 'J');
        $pdf->SetX(125);
        $pdf->Cell(10, 5, "___________________________________", 0, 0, 'J');
        $pdf->Ln(5);
	$pdf->Cell(10, 5, utf8_decode('El Paciente'), 0, 0, 'J');			
        $pdf->SetX(125);
	$pdf->Cell(10, 5, utf8_decode('El Medico Informante'), 0, 0, 'J');
        $pdf->Ln(20);
	//firma 2
	$pdf->SetXY(10, 90);
	$pdf->Cell(10, 5, "___________________________________", 0, 0, 'J');	
	$pdf->SetXY(10, 95);
	$pdf->Cell(10, 5, utf8_decode('El Medico Tratante'), 0, 0, 'J');	
        $pdf->SetXY(125, 90);
	$pdf->Cell(10, 5, "___________________________________", 0, 0, 'J');	
	$pdf->SetXY(125, 95);
	$pdf->Cell(10, 5, utf8_decode('El Testigo'), 0, 0, 'J');	
        //datos paciente
	$pdf->SetXY(10, 110);
	$pdf->Cell(10, 5, "Datos contacto del paciente:", 0, 0, 'J');	
        $pdf->SetXY(10, 118);
	$pdf->Cell(18, 5, "Celular:", 0, 0, 'J');
	$pdf->Cell(18, 5, $cliente->celular, 0, 0, 'J');	
	$pdf->SetXY(10, 123);
	$pdf->Cell(18, 5, utf8_decode("Teléfono:"), 0, 0, 'J');
	$pdf->Cell(18, 5, $cliente->telefono, 0, 0, 'J');
	$pdf->SetXY(10, 128);
	$pdf->Cell(18, 5, "Correo:", 0, 0, 'J');
	$pdf->Cell(18, 5, $cliente->email, 0, 0, 'J');
	$pdf->SetXY(10, 20);
        $rutafirma = "firmaCliente/".$model->firma; //ruta firma cliente	
	$pdf->Image($rutafirma,17, 36, 50, 15);	
        $rutadoctor = "firmaCliente/".'sede_1'; //ruta firma doctor	
	$pdf->Image($rutadoctor.'.PNG',17, 75, 35, 18);
    }

    function Footer() 
    { 
      $this->Text(180, 290, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');	  
    } 
	
}
global $consentimiento_cliente_pk;
$consentimiento_cliente_pk = $model->consentimiento_cliente_pk;
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model,$formato);
$pdf->SetFont('Arial', '', 10);
$pdf->Output("Consentimiento$model->identificacion.pdf", 'D');

exit
		
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