<?php

namespace app\controllers;

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
use app\models\Seccion;
use app\models\SeccionTipo;
use app\models\Cliente;
use app\models\Sedes;
use yii\helpers\Url;
use app\models\FormFiltroSeccion;
use app\models\FormSeccion;
use app\models\FormSeccionAnula;
use yii\web\UploadedFile;

class SeccionController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $form = new FormFiltroSeccion;            
            $identificacion = null;            
            if ($form->load(Yii::$app->request->get())) {
                if ($form->validate()) {                    
                    $identificacion = Html::encode($form->identificacion);
                    $cliente = Cliente::find()->where(['=','identificacion',$identificacion])->one();
                    $table = Seccion::find()
                            ->where(['<>', 'seccion_pk', 0])
                            ->andFilterWhere(['like', 'cliente_fk', $cliente->cliente_pk])                            
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
                } else {
                    $form->getErrors();
                }
            } else {
                $table = Seccion::find()
                        ->where(['<>', 'seccion_pk', 0])                                                    
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
            }
            return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,
            ]);
        } else {
            return $this->redirect(["seccion/index"]);
        }
    }        
    
    public function actionNuevo() {
        $model = new FormSeccion;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {                                
                $seccion = new Seccion();                    
                $seccion->cliente_fk = $model->cliente_fk;
                $seccion->seccion_tipo_fk = $model->seccion_tipo_fk;
                $seccion->sede_fk = $model->sede_fk;
                $seccion->fecha = $model->fecha_seccion;
                $seccion->hora_seccion = $model->hora_seccion;
                $seccion->valor_seccion = $model->valor_seccion;
                $seccion->usuario_pago = Yii::$app->user->identity->id;
                $seccion->usuario = Yii::$app->user->identity->id;
                $seccion->observaciones = $model->observaciones;                    
                $seccion->estado_pagado = "SI";
                $seccion->total_pago = $seccion->valor_seccion;
                $seccion->fecha_pago = date('Y-m-d H:i:s');
                $seccion->save(false);                  
                return $this->redirect(["seccion/index"]);  
            }        
        }        
        return $this->render("nuevo", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }

    public function actionAnulacion($seccion_pk) {
        $model = new FormSeccionAnula;
        $msg = null;
        $tipomsg = null;
        if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $table = Seccion::find()->where(['seccion_pk' => $seccion_pk])->one();
                if ($table) {                                        
                    $table->estado_anulado = 'SI';
                    $table->fecha_anulado = $model->fechaanulado.' '.date("H:i:s");
                    $table->usuario_anulado = Yii::$app->user->identity->id;                                     
                    if ($table->save(false)) {
                        $msg = "El registro ha sido actualizado correctamente";                        
                        return $this->redirect(["seccion/index"]);   
                    } else {
                        $msg = "El registro no sufrio ningun cambio";
                        $tipomsg = "danger";                        
                    }                                        
                } else {
                    $msg = "El registro seleccionado no ha sido encontrado";
                    $tipomsg = "danger";
                }
            } else {
                $model->getErrors();
            }
        }                
        return $this->render("anulacion", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
    }        
    
    public function actionImprimirfacturatirilla($seccion_pk) {         
        $model = Seccion::find()->where(['seccion_pk' => $seccion_pk])->one();
        $usuario = \app\models\Users::find()->where(['=','id',$model->usuario])->one();
        return $this->render("imprimirfacturatirilla", ["model" => $model, "usuario" => $usuario]);
    }
    
    public function actionImprimirfactura($seccion_pk) {
        $model = Seccion::find()->where(['seccion_pk' => $seccion_pk])->one();
        $usuario = \app\models\Users::find()->where(['=','id',$model->usuario])->one();
        return $this->render("imprimirfactura", ["model" => $model, "usuario" => $usuario]);
    }

}
