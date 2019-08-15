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
	  $this->Ln(10);
          $this->EncabezadoDetalles();
        
        
    }

    function EncabezadoDetalles() {
        $this->Ln(7);
        $header = array('FECHA', 'PIEZA DE MANO', 'POTENCIA[W] Y TIEMPO', 'ENERGIA ACUMULADA[KJ]', 'AREA', 'N. PASES');
        $this->SetFillColor(200, 200, 200);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(.2);
        $this->SetFont('', 'B', 8);

        //creamos la cabecera de la tabla.
        $w = array(25, 35, 40, 45, 25, 25);
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
