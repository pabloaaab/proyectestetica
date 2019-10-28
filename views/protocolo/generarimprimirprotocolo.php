			<?php

use inquid\pdf\FPDF;
use app\models\Habeasdata;
use app\models\FormatoAutorizacion;
use app\models\Cliente;

class PDF extends FPDF {

    function Header() { 
	  $this->SetFillColor(236, 236, 236);	
	  $this->Image('images/logo.png', 10, 10, 45, 35);
          //$this->Image('images/logo.png', 12, 15, 25, 25);
	  $this->SetFont('Helvetica','B',15); 
          $this->SetXY(55, 10); 
          $this->Cell(155,7,'BEAUTY ICE',0,0,'C',1);
	  $this->SetFont('Helvetica','B',12);
	  $this->SetXY(55, 17); 
          $this->Cell(155,7,'Nit: 42897128-6',0,0,'C',1);	
          $this->SetXY(55, 24); 
          $this->Cell(155,7,'Cr. 48 Nro 25 AA sur 70',0,0,'C',1);
	  $this->SetXY(55, 31); 
          $this->Cell(155,7,'Complex Las Vegas - Local 103',0,0,'C',1);
	  $this->SetXY(55, 38); 
          $this->Cell(155,7,'Telefono: 3206946957',0,0,'C',1);
	  $this->SetXY(10, 50); 
          $this->Cell(30,7,'PROTOCOLOS',0,0,'C',0);
          $this->SetFont('Helvetica','B',12);
          $this->SetXY(10, 60); 
          $this->Cell(40,7,'FOTOTIPO DE PIEL:',0,0,'C',0);
          $this->SetXY(110, 60); 
          $this->Cell(43,7,'FOTOTIPO DE AREA:',0,0,'C',0);
          $this->Ln(7);
          $this->EncabezadoDetalles();
        
        
    }

    function EncabezadoDetalles() {
        $this->Ln(5);
        $header = array('FECHA', 'PIEZA DE MANO', 'POTENCIA[W] Y TIEMPO', 'ENERGIA ACUMULADA[KJ]', 'AREA', 'N. PASES');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(25, 35, 35, 40, 45, 15);
        for ($i = 0; $i < count($header); $i++)
            if ($i == 0 || $i == 1)
                $this->Cell($w[$i], 5, $header[$i], 1, 0, 'C', 1);
            else
                $this->Cell($w[$i], 5, $header[$i], 1, 0, 'C', 1);

        //Restauración de colores y fuentes
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $this->Ln(5);    
    }
    
    function Body($pdf,$model) {
        $detalles = \app\models\Protocolo::find()->where(['=','consentimiento_cliente_fk',$model->consentimiento_cliente_pk])->all();
        $pdf->SetX(10);
        $pdf->SetFont('Arial', '', 8);        
        foreach ($detalles as $detalle) {             
            $pdf->Cell(25, 5, $detalle->fecha, 1, 0, 'C');            
            $pdf->Cell(35, 5, $detalle->pieza_mano, 1, 0, 'C');
            $pdf->Cell(35, 5, $detalle->potencia_tiempo, 1, 0, 'C');
            $pdf->Cell(40, 5, $detalle->energia, 1, 0, 'C');
            $pdf->Cell(45, 5, $detalle->area, 1, 0, 'C');
            $pdf->Cell(15, 5, $detalle->pases, 1, 0, 'C');            
            $pdf->Ln();
        }
    }

    function Footer() 
    { 
	$this->Text(180, 275, utf8_decode('Página ') . $this->PageNo() . ' de {nb}');  
    } 
	
} 
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Body($pdf,$model);
$pdf->AliasNbPages();
$pdf->SetFont('Arial', '', 10);
$pdf->Output("protocolos$model->identificacion.pdf", 'D');

exit;
		
?>
