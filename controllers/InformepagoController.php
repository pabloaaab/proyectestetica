<?php

namespace app\controllers;

use app\models\FormFiltroInformesPagos;
use app\models\Sedes;
use app\models\Seccion;
use app\models\Cliente;
use Codeception\Lib\HelperModule;
use yii;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;
use yii\web\Session;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\bootstrap\Modal;
use yii\helpers\ArrayHelper;
use moonland\phpexcel\Excel;
use app\models\UsuarioDetalle;
use PHPExcel;



class InformepagoController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroInformesPagos;
            //$nivel = null;
            $identificacion = null;
            $fecha_pago = null;
            $sede_fk = null;                        
            $anio_mes_dia = null;
            $seccion_pk = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                    
                    $identificacion = Html::encode($form->identificacion);
                    $fecha_pago = Html::encode($form->fecha_pago);
                    $sede_fk = Html::encode($form->sede_fk);                    
                    $anio_mes_dia = Html::encode($form->anio_mes_dia);
                    $seccion_pk = Html::encode($form->seccion_pk);
                    if ($anio_mes_dia == "dia"){
                        $fecha_pago = $fecha_pago;
                    }
                    if ($anio_mes_dia == "mes"){
                        $fecha_pago = date('Y-m', strtotime($fecha_pago));
                    }
                    if ($anio_mes_dia == "anio"){
                        $fecha_pago = date('Y', strtotime($fecha_pago));
                    }
                    $cliente = Cliente::find()->where(['=','identificacion',$identificacion])->one();
                    if($cliente){
                        $dato = $cliente->cliente_pk;
                    }else{
                        $dato = "";
                    }
                    $table = Seccion::find()                                                        
                            ->andFilterWhere(['like', 'cliente_fk', $dato])
                            ->andFilterWhere(['like', 'fecha_pago', $fecha_pago])                            
                            ->andFilterWhere(['like', 'sede_fk', $sede_fk])
                            ->andFilterWhere(['=', 'seccion_pk', $seccion_pk])
                            ->orderBy('seccion_pk desc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count()
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                                
                $connection = Yii::$app->getDb();
                if ($identificacion != null){
                    $d1= " and cliente_fk = '". $cliente->cliente_pk."'";
                }else{
                    $d1= " ";
                }
                if ($fecha_pago != null){
                    $d2= " and fecha_pago like '%". $fecha_pago."%'";
                }else{
                    $d2= " ";
                }
                if ($sede_fk != null){
                    $d3= " and sede_fk = '". $sede_fk."'";
                }else{
                    $d3= " ";
                }                
                if ($seccion_pk != null){
                    $d5= " and seccion_pk = '". $seccion_pk."'";
                }else{
                    $d5= " ";
                }
                $command = $connection->createCommand("
                    SELECT                         
                        SUM(IF(sede_fk = '1' and estado_anulado = '',total_pago,0))   AS pagosmedellin,
                        SUM(IF(sede_fk = '1' and estado_anulado = 'si',total_pago,0))   AS pagosmedellinanulado,
                        SUM(IF(sede_fk = '2' and estado_anulado = '',total_pago,0))   AS pagosenvigado,
                        SUM(IF(sede_fk = '2' and estado_anulado = 'si',total_pago,0))   AS pagosenvigadoanulado,
                        SUM(IF(sede_fk = '3' and estado_anulado = '',total_pago,0))   AS pagossinsede,
                        SUM(IF(sede_fk = '3' and estado_anulado = 'si',total_pago,0))   AS pagossinsedeanulado
                        FROM seccion where seccion_pk <> 0 ".$d1.$d2.$d3.$d5);
                                            

                $result = $command->queryAll();
                $subtotal = $result[0]['pagosmedellin'] + $result[0]['pagosenvigado'] + $result[0]['pagossinsede'];
                $totalanulado = $result[0]['pagosmedellinanulado'] + $result[0]['pagosenvigadoanulado'] + $result[0]['pagossinsedeanulado'];
                $grantotal = $subtotal - $totalanulado;
                } else {
                    $form->getErrors();
                }
                
                if(isset($_POST['excel'])){
                    $cliente = Cliente::find()->where(['=','identificacion',$identificacion])->one();
                    if($cliente){
                        $dato = $cliente->cliente_pk;
                    }else{
                        $dato = "";
                    }
                    $table = Seccion::find()                                                        
                            ->andFilterWhere(['like', 'cliente_fk', $dato])
                            ->andFilterWhere(['like', 'fecha_pago', $fecha_pago])                            
                            ->andFilterWhere(['like', 'sede_fk', $sede_fk])
                            ->andFilterWhere(['=', 'seccion_pk', $seccion_pk])
                            ->orderBy('seccion_pk desc');
                    
                    $model = $table->all();
                    $this->actionExcel($model);                    
                }
            } else {
                $table = Seccion::find()                        
                        ->orderBy('seccion_pk desc');
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 20,
                    'totalCount' => $count->count(),
                ]);
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();                
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("
                    SELECT                         
                        SUM(IF(sede_fk = '1' and estado_anulado = '',total_pago,0))   AS pagosmedellin,
                        SUM(IF(sede_fk = '1' and estado_anulado = 'si',total_pago,0))   AS pagosmedellinanulado,
                        SUM(IF(sede_fk = '2' and estado_anulado = '',total_pago,0))   AS pagosenvigado,
                        SUM(IF(sede_fk = '2' and estado_anulado = 'si',total_pago,0))   AS pagosenvigadoanulado,
                        SUM(IF(sede_fk = '3' and estado_anulado = '',total_pago,0))   AS pagossinsede,
                        SUM(IF(sede_fk = '3' and estado_anulado = 'si',total_pago,0))   AS pagossinsedeanulado
                        FROM seccion
                    ");

                $result = $command->queryAll();
                $subtotal = $result[0]['pagosmedellin'] + $result[0]['pagosenvigado'] + $result[0]['pagossinsede'];
                $totalanulado = $result[0]['pagosmedellinanulado'] + $result[0]['pagosenvigadoanulado'] + $result[0]['pagossinsedeanulado'];
                $grantotal = $subtotal - $totalanulado;
                if(isset($_POST['excel'])){
                    //$this->actionExcel($model);                    
                }
            }
            
            return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,                        
                        'result' => $result,
                        'totalanulado' => $totalanulado,
                        'subtotal' => $subtotal,
                        'grantotal' => $grantotal
            ]);
        } else {
            return $this->redirect(["site/login"]);
        }
    }
    
    public function actionIndexcc() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroInformesPagos;
            //$nivel = null;
            $identificacion = null;
            $fecha_pago = null;
            $sede_fk = null;                        
            $anio_mes_dia = null;
            $seccion_pk = null;
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                    
                    $identificacion = Html::encode($form->identificacion);
                    $fecha_pago = Html::encode($form->fecha_pago);
                    $sede_fk = Html::encode($form->sede_fk);                    
                    $anio_mes_dia = Html::encode($form->anio_mes_dia);
                    $seccion_pk = Html::encode($form->seccion_pk);
                    if ($anio_mes_dia == "dia"){
                        $fecha_pago = $fecha_pago;
                    }
                    if ($anio_mes_dia == "mes"){
                        $fecha_pago = date('Y-m', strtotime($fecha_pago));
                    }
                    if ($anio_mes_dia == "anio"){
                        $fecha_pago = date('Y', strtotime($fecha_pago));
                    }
                    $cliente = Cliente::find()->where(['=','identificacion',$identificacion])->one();
                    if($cliente){
                        $dato = $cliente->cliente_pk;
                    }else{
                        $dato = "";
                    }
                    $table = Seccion::find()
                            ->where(['=','centro_costo',0])
                            ->andFilterWhere(['like', 'cliente_fk', $dato])
                            ->andFilterWhere(['like', 'fecha_pago', $fecha_pago])                            
                            ->andFilterWhere(['like', 'sede_fk', $sede_fk])
                            ->andFilterWhere(['=', 'seccion_pk', $seccion_pk])
                            ->orderBy('seccion_pk desc');
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 20,
                        'totalCount' => $count->count()
                    ]);
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                                
                $connection = Yii::$app->getDb();
                if ($identificacion != null){
                    $d1= " and cliente_fk = '". $cliente->cliente_pk."'";
                }else{
                    $d1= " ";
                }
                if ($fecha_pago != null){
                    $d2= " and fecha_pago like '%". $fecha_pago."%'";
                }else{
                    $d2= " ";
                }
                if ($sede_fk != null){
                    $d3= " and sede_fk = '". $sede_fk."'";
                }else{
                    $d3= " ";
                }                
                if ($seccion_pk != null){
                    $d5= " and seccion_pk = '". $seccion_pk."'";
                }else{
                    $d5= " ";
                }
                $command = $connection->createCommand("
                    SELECT                         
                        SUM(IF(sede_fk = '1' and estado_anulado = '' and centro_costo = '0',total_pago,0))   AS pagosmedellin,
                        SUM(IF(sede_fk = '1' and estado_anulado = 'si' and centro_costo = '0',total_pago,0))   AS pagosmedellinanulado,
                        SUM(IF(sede_fk = '2' and estado_anulado = '' and centro_costo = '0',total_pago,0))   AS pagosenvigado,
                        SUM(IF(sede_fk = '2' and estado_anulado = 'si' and centro_costo = '0',total_pago,0))   AS pagosenvigadoanulado,
                        SUM(IF(sede_fk = '3' and estado_anulado = '' and centro_costo = '0',total_pago,0))   AS pagossinsede,
                        SUM(IF(sede_fk = '3' and estado_anulado = 'si' and centro_costo = '0',total_pago,0))   AS pagossinsedeanulado
                        FROM seccion where seccion_pk <> 0 ".$d1.$d2.$d3.$d5);
                                            

                $result = $command->queryAll();
                $subtotal = $result[0]['pagosmedellin'] + $result[0]['pagosenvigado'] + $result[0]['pagossinsede'];
                $totalanulado = $result[0]['pagosmedellinanulado'] + $result[0]['pagosenvigadoanulado'] + $result[0]['pagossinsedeanulado'];
                $grantotal = $subtotal - $totalanulado;
                } else {
                    $form->getErrors();
                }
                
                if(isset($_POST['excel'])){
                    $cliente = Cliente::find()->where(['=','identificacion',$identificacion])->one();
                    if($cliente){
                        $dato = $cliente->cliente_pk;
                    }else{
                        $dato = "";
                    }
                    $table = Seccion::find()
                            ->where(['=','centro_costo',0])
                            ->andFilterWhere(['like', 'cliente_fk', $dato])
                            ->andFilterWhere(['like', 'fecha_pago', $fecha_pago])                            
                            ->andFilterWhere(['like', 'sede_fk', $sede_fk])
                            ->andFilterWhere(['=', 'seccion_pk', $seccion_pk])
                            ->orderBy('seccion_pk desc');
                    
                    $model = $table->all();
                    $this->actionExcelcc($model);                    
                }
            } else {
                $table = Seccion::find()
                        ->where(['=','centro_costo',0])
                        ->orderBy('seccion_pk desc');
                $count = clone $table;
                $pages = new Pagination([
                    'pageSize' => 20,
                    'totalCount' => $count->count(),
                ]);
                $model = $table
                        ->offset($pages->offset)
                        ->limit($pages->limit)
                        ->all();                
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("
                    SELECT                         
                        SUM(IF(sede_fk = '1' and estado_anulado = '' and centro_costo = '0',total_pago,0))   AS pagosmedellin,
                        SUM(IF(sede_fk = '1' and estado_anulado = 'si' and centro_costo = '0',total_pago,0))   AS pagosmedellinanulado,
                        SUM(IF(sede_fk = '2' and estado_anulado = '' and centro_costo = '0',total_pago,0))   AS pagosenvigado,
                        SUM(IF(sede_fk = '2' and estado_anulado = 'si' and centro_costo = '0',total_pago,0))   AS pagosenvigadoanulado,
                        SUM(IF(sede_fk = '3' and estado_anulado = '' and centro_costo = '0',total_pago,0))   AS pagossinsede,
                        SUM(IF(sede_fk = '3' and estado_anulado = 'si' and centro_costo = '0',total_pago,0))   AS pagossinsedeanulado
                        FROM seccion
                    ");

                $result = $command->queryAll();
                $subtotal = $result[0]['pagosmedellin'] + $result[0]['pagosenvigado'] + $result[0]['pagossinsede'];
                $totalanulado = $result[0]['pagosmedellinanulado'] + $result[0]['pagosenvigadoanulado'] + $result[0]['pagossinsedeanulado'];
                $grantotal = $subtotal - $totalanulado;
                if(isset($_POST['excel'])){
                    //$this->actionExcelcc($model);                    
                }
            }
            
            return $this->render('indexcc', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,                        
                        'result' => $result,
                        'totalanulado' => $totalanulado,
                        'subtotal' => $subtotal,
                        'grantotal' => $grantotal
            ]);
        } else {
            return $this->redirect(["site/login"]);
        }
    }
    
    public function actionCc($id) {
            if (Yii::$app->request->get()) {
                $evento = Seccion::findOne($id);
                if ($evento->centro_costo == 0){
                    $evento->centro_costo = 1;                    
                }else{
                    $evento->centro_costo = 0;                    
                }            
                $evento->save(false);
                $this->redirect(["informepago/index"]);
            } else {
                $this->redirect(["informepago/index"]);
            }
        }
    
    public function actionExcel($model) {
        //$costoproducciondiario = CostoProduccionDiaria::find()->all();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true); 
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true); 
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Nro Pago')
                    ->setCellValue('B1', 'Cliente')
                    ->setCellValue('C1', 'Fecha Pago')                    
                    ->setCellValue('D1', 'Seccion')
                    ->setCellValue('E1', 'Valor Seccion')
                    ->setCellValue('F1', 'Valor Pago')
                    ->setCellValue('G1', 'Sede')
                    ->setCellValue('H1', 'Pagado')
                    ->setCellValue('I1', 'Anulado')
                    ->setCellValue('J1', 'Observaciones');

        $i = 2;
        
        foreach ($model as $val) {
            if ($val->estado_anulado == "") {
                $anulado = "NO";
            } else {
                $anulado = "SI";
            }
            if ($val->estado_pagado == "SI") {
                $pagado = "SI";
            } else {
                $pagado = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->seccion_pk)
                    ->setCellValue('B' . $i, $val->clienteFk->nombrecompleto)
                    ->setCellValue('C' . $i, $val->fecha_pago)
                    ->setCellValue('D' . $i, $val->seccionTipoFk->tipo)
                    ->setCellValue('E' . $i, $val->valor_seccion)
                    ->setCellValue('F' . $i, $val->total_pago)
                    ->setCellValue('G' . $i, $val->sedeFk->sede)
                    ->setCellValue('H' . $i, $pagado)
                    ->setCellValue('I' . $i, $anulado)
                    ->setCellValue('J' . $i, $val->observaciones);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('informe');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="informe.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        //return $model;
        exit;
        
    }
    
    public function actionExcelcc($model) {
        //$costoproducciondiario = CostoProduccionDiaria::find()->all();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
            ->setLastModifiedBy("EMPRESA")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true); 
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true); 
        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Nro Pago')
                    ->setCellValue('B1', 'Cliente')
                    ->setCellValue('C1', 'Fecha Pago')                    
                    ->setCellValue('D1', 'Seccion')
                    ->setCellValue('E1', 'Valor Seccion')
                    ->setCellValue('F1', 'Valor Pago')
                    ->setCellValue('G1', 'Sede')
                    ->setCellValue('H1', 'Pagado')
                    ->setCellValue('I1', 'Anulado')
                    ->setCellValue('J1', 'Observaciones');

        $i = 2;
        
        foreach ($model as $val) {
            if ($val->estado_anulado == "") {
                $anulado = "NO";
            } else {
                $anulado = "SI";
            }
            if ($val->estado_pagado == "SI") {
                $pagado = "SI";
            } else {
                $pagado = "NO";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->seccion_pk)
                    ->setCellValue('B' . $i, $val->clienteFk->nombrecompleto)
                    ->setCellValue('C' . $i, $val->fecha_pago)
                    ->setCellValue('D' . $i, $val->seccionTipoFk->tipo)
                    ->setCellValue('E' . $i, $val->valor_seccion)
                    ->setCellValue('F' . $i, $val->total_pago)
                    ->setCellValue('G' . $i, $val->sedeFk->sede)
                    ->setCellValue('H' . $i, $pagado)
                    ->setCellValue('I' . $i, $anulado)
                    ->setCellValue('J' . $i, $val->observaciones);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('informecc');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="informecc.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        //return $model;
        exit;
        
    }

}
