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
    }
    
    function Body($pdf,$model) {
        //Titulo
	$pdf->SetXY(10, 50);
	$pdf->SetFont('Arial','B',14);	
	$pdf->MultiCell(70,5, 'FICHA DE APERTURA DE HISTORIA CLINICA',0,'C');
	//Fecha
	$pdf->SetXY(130, 50);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(120, 6, 'Fecha: '.$model->fecha_creacion, 0, 0, 'C');
	//Datos personales
	$pdf->SetXY(10, 70);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(50, 5, 'DATOS PERSONALES:', 0, 0, 'L');
	$pdf->SetXY(10, 80);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(38, 5, 'Nombres y apellidos:', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(100, 5, $model->nombre, 0, 0, 'L');
	$pdf->SetXY(10, 85);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(45, 5, 'Documento de identidad:', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(30, 5, $model->identificacion, 0, 0, 'L');
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(15, 5, 'Sexo:', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(20, 5, $model->sexo, 0, 0, 'L');
	$pdf->SetXY(10, 90);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(21, 5, 'Ocupacion:', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(90, 5, $model->ocupacion, 0, 0, 'L');
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(33, 5, 'Fecha nacimiento:', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(22, 5, $model->fecha_nacimiento, 0, 0, 'L');
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(12, 5, 'Edad:', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(13, 5, $model->edad, 0, 0, 'L');
	$pdf->SetXY(10, 95);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(41, 5, 'Direccion de domicilio:', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(85, 5, $model->direccion, 0, 0, 'L');
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(20, 5, 'Telefono:', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(50, 5, $model->telefono, 0, 0, 'L');
	$pdf->SetXY(10, 100);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(38, 5, 'Lugar de residencia: ', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(150, 5, $model->lugar_residencia, 0, 0, 'L');
	$pdf->SetXY(10, 105);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(40, 5, 'Aseguradora en salud:', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(160, 5, $model->aseguradora_salud, 0, 0, 'L');
	$pdf->SetXY(10, 110);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(25, 5, 'Estado civil:', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(170, 5, $model->estado_civil, 0, 0, 'L');
	$pdf->SetXY(10, 115);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15, 5, 'Email:', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(120, 5, $model->email, 0, 0, 'L');
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(18, 5, 'Celular: ', 0, 0, 'L');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(40, 5, $model->celular, 0, 0, 'L');
	//Habitos
	$pdf->SetXY(10, 125);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(50, 5, 'HABITOS:', 0, 0, 'L');
	$pdf->SetXY(10, 135);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(50, 5, 'Su consumo de grasa es:', 0, 0, 'L');
	$grasa = 0;
	if ($model->consumo_grasa == 1){
		$grasa = 'Alto';
	}
	if ($model->consumo_grasa == 2){
		$grasa = 'Normal';
	}
	if ($model->consumo_grasa == 3){
		$grasa = 'Bajo';
	}
        if ($model->consumo_grasa == 0){
		$grasa = 'Sin Definir';
	}
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(70, 5, $grasa, 0, 0, 'L');
	$pdf->SetXY(10, 140);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(50, 5, 'Su consumo de azucar es:', 0, 0, 'L');
	$azucar = 0;
	if ($model->consumo_azucar == 1){
		$azucar = 'Alto';
	}
	if ($model->consumo_azucar == 2){
		$azucar = 'Normal';
	}
	if ($model->consumo_azucar == 3){
		$azucar = 'Bajo';
	}
        if ($model->consumo_azucar == 0){
		$azucar = 'Sin Definir';
	}
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(70, 5, $azucar, 0, 0, 'L');
	$pdf->SetXY(10, 145);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(50, 5, 'Fuma:', 0, 0, 'L');
	$fuma = 0;
	if ($model->fuma == 1){
		$fuma = 'Si';
	}
	if ($model->fuma == 0){
		$fuma = 'No';
	}
        if ($model->fuma == 0){
		$fuma = 'Sin Definir';
	}
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(20, 5, $fuma, 0, 0, 'L');
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(30, 5, 'Cuantos al dia: ', 0, 0, 'L');
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(10, 5, $model->cuanto_diario, 0, 0, 'L');
	$pdf->SetXY(10, 150);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(50, 5, 'Consume alcohol:', 0, 0, 'L');
	$alcohol = 0;
	if ($model->alcohol == 1){
		$alcohol = 'Si';
	}
	if ($model->alcohol == 0){
		$alcohol = 'No';
	}
        if ($model->alcohol == 2){
		$alcohol = 'Sin Definir';
	}
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(20, 5, $alcohol, 0, 0, 'L');
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(30, 5, 'Consumo: ', 0, 0, 'L');
	$consumo = 0;
	if ($model->consumo_alcohol == 1){
		$consumo = 'Diario';
	}
	if ($model->consumo_alcohol == 2){
		$consumo = 'Semanal';
	}
	if ($model->consumo_alcohol == 3){
		$consumo = 'Ocasional';
	}
        if ($model->consumo_alcohol == 0){
		$consumo = 'Sin Definir';
	}
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(10, 5, $consumo, 0, 0, 'L');
	$pdf->SetXY(10, 155);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(50, 5, 'Duerme bien:', 0, 0, 'L');
	$duerme = 0;
	if ($model->duerme_bien == 1){
		$duerme = 'Si';
	}
	if ($model->duerme_bien == 0){
		$duerme = 'No';
	}
        if ($model->duerme_bien == 2){
		$duerme = 'Sin Definir';
	}
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(20, 5, $duerme, 0, 0, 'L');
	$pdf->SetXY(10, 160);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(50, 5, 'Hace ejercicio:', 0, 0, 'L');
	$ejercio = 0;
	if ($model->hace_ejercicio == 1){
		$ejercio = 'Si';
	}
	if ($model->hace_ejercicio == 0){
		$ejercio = 'No';
	}
        if ($model->hace_ejercicio == 2){
		$ejercio = 'Sin Definir';
	}
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(20, 5, $ejercio, 0, 0, 'L');
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(30, 5, 'Clase: ', 0, 0, 'L');
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(20, 5, $model->clase, 0, 0, 'L');
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(30, 5, 'Frecuencia: ', 0, 0, 'L');
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(10, 5, $model->frecuencia, 0, 0, 'L');
	$pdf->SetXY(10, 170);
	$pdf->SetFont('Arial','B',12);
	//Otros
	$pdf->SetXY(10, 173);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(50, 5, 'OTROS:', 0, 0, 'L');
	$pdf->SetXY(10, 183);
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(30, 5, 'Motivo de la Consulta: ', 0, 0, 'L');
	$pdf->SetFont('Arial','u',10);
	$pdf->SetXY(10, 188);
	$pdf->MultiCell(0,5, $model->celular,0,'L');
	$pdf->SetXY(10, 194);
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(30, 5, 'Enfermedad Actual: ', 0, 0, 'L');
	$pdf->SetFont('Arial','u',10);
	$pdf->SetXY(10, 199);
	$pdf->MultiCell(0,5, $model->celular,0,'L');
	$pdf->SetXY(10, 205);
	$pdf->SetFont('Arial','b',10);
	$pdf->Cell(30, 5, 'Revision por Sistema: ', 0, 0, 'L');
	$pdf->SetFont('Arial','u',10);
	$pdf->SetXY(10, 210);
	$pdf->MultiCell(0,5, $model->celular,0,'L');
	//Antecedentes personales
	$pdf->SetXY(10, 220);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(50, 5, 'ANTECEDENTES PERSONALES:', 0, 0, 'L');
	$pdf->SetXY(10, 228);
	$pdf->Cell(50, 5, 'HOMBRE:', 0, 0, 'L');
	$pdf->SetXY(10, 235);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(30, 5, 'Patologia:', 0, 0, 'L');
	$patologia = 0;
	if ($model->aph_patologia == 1){
		$patologia = 'Si';
	}
	if ($model->aph_patologia == 0){
		$patologia = 'No';
	}
        if ($model->aph_patologia == 2){
		$patologia = 'S/N';
	}
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(10, 5, $patologia, 0, 0, 'L');
	$pdf->Cell(50, 5, $model->aph_patologia_t, 0, 0, 'L');
	$pdf->SetXY(10, 240);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(30, 5, 'Quirurgicos:', 0, 0, 'L');
	$quirurgicos = 0;
	if ($model->aph_quirurgicos == 1){
		$quirurgicos = 'Si';
	}
	if ($model->aph_quirurgicos == 0){
		$quirurgicos = 'No';
	}
        if ($model->aph_quirurgicos == 2){
		$quirurgicos = 'S/N';
	}
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(10, 5, $quirurgicos, 0, 0, 'L');
	$pdf->Cell(50, 5, $model->aph_quirurgicos_t, 0, 0, 'L');
	$pdf->SetXY(10, 245);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(30, 5, 'Alergicos:', 0, 0, 'L');
	$alergicos = 0;
	if ($model->aph_alergicos == 1){
		$alergicos = 'Si';
	}
	if ($model->aph_alergicos == 0){
		$alergicos = 'No';
	}
        if ($model->aph_alergicos == 2){
		$alergicos = 'S/N';
	}
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(10, 5, $alergicos, 0, 0, 'L');
	$pdf->Cell(50, 5, $model->aph_alergicos_t, 0, 0, 'L');
	$pdf->SetXY(10, 250);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(30, 5, 'Toxicos:', 0, 0, 'L');
	$toxicos = 0;
	if ($model->aph_toxicos == 1){
		$toxicos = 'Si';
	}
	if ($model->aph_toxicos == 0){
		$toxicos = 'No';
	}
        if ($model->aph_toxicos == 2){
		$toxicos = 'S/N';
	}
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(10, 5, $toxicos, 0, 0, 'L');
	$pdf->Cell(50, 5, $model->aph_toxicos_t, 0, 0, 'L');
	//mujer
	$pdf->Ln(8);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(50, 5, 'MUJER:', 0, 0, 'L');	
	$pdf->Ln(8);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Agregar Ant Obstreticos:', 0, 0, 'L');
	$pdf->SetFont('Arial','u',10);
	$agregar = 0;
	if ($model->apm_agregar == 1){
		$agregar = 'Si';
	}
	if ($model->apm_agregar == 0){
		$agregar = 'No';
	}
        if ($model->apm_agregar == 2){
		$agregar = 'S/N';
	}
	$pdf->Cell(8, 5, $agregar, 0, 0, 'L');	
	$pdf->Cell(140, 5, $model->apm_agregar_t, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Menarca:', 0, 0, 'L');
	$pdf->SetFont('Arial','u',10);
	$menarca = 0;
	if ($model->apm_menarca == 1){
		$menarca = 'Si';
	}
	if ($model->apm_menarca == 0){
		$menarca = 'No';
	}
        if ($model->apm_menarca == 2){
		$menarca = 'Sin Definir';
	}
	$pdf->Cell(8, 5, $menarca, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Fecha Ultima Mestruacion:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->apm_fecha_ultima_m, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Embarazo:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$embarazo = 0;
	if ($model->apm_embarazo == 1){
		$embarazo = 'Si';
	}
	if ($model->apm_embarazo == 0){
		$embarazo = 'No';
	}
        if ($model->apm_embarazo == 2){
		$embarazo = 'Sin Definir';
	}
	$pdf->Cell(8, 5, $embarazo, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Metodo de Planificacion:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->apm_metodo_planificar, 0, 0, 'L');
	$pdf->Ln(10);
	//antecedentes personales
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(50, 5, 'ANTECEDENTES FAMILIARES:', 0, 0, 'L');
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Examen Fisico:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->examen_fisico, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Presion Arterial:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->presion_arterial, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Frecuencia Cardiaca:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->frecuencia_cardiaca, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Frecuencia Respiratoria:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->frecuencia_respiratoria, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Peso:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(20, 5, $model->peso, 0, 0, 'L');
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20, 5, 'Talla:', 0, 0, 'L');
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(20, 5, $model->talla, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'IMC:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->imc, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Cabeza:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->cabeza, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Cuello:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->cuello, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Cardio Respiratorio:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->cardio_respiratorio, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Abdomen:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->abdomen, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Extremidades:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->extremidades, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Piel:', 0, 0, 'L');	
	$pdf->SetFont('Arial','u',10);
	$pdf->Cell(140, 5, $model->piel, 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(53, 5, 'Observaciones:', 0, 0, 'L');	
	$pdf->SetFont('Arial','b',10);
	$pdf->MultiCell(0,5, $model->af_observaciones,0,'J');
	$pdf->Ln(10);	
	//OBSERVACIONES
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(50, 5, 'OBSERVACIONES GENERALES:', 0, 0, 'L');
	$pdf->Ln(6);
	$pdf->SetFont('Arial','',10);
	$pdf->MultiCell(0,5, $model->observaciones,0,'J');
	$pdf->Ln(10);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15, 5, 'FIRMA:', 0, 0, 'L');
	$pdf->Cell(30, 5, '_______________________________________________________', 0, 0, 'L');
	//
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
$pdf->Output("Habeasdata.pdf", 'D');

exit;
		
?>
