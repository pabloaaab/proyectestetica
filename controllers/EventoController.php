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
use app\models\Eventos;
use app\models\Maquina;
use app\models\SeccionTipo;
use app\models\Sedes;
use app\models\FormEvento;
use yii\helpers\Url;
use app\models\FormFiltroEvento;
use yii\web\UploadedFile;

    class EventoController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
                $form = new FormFiltroEvento;
                $fecha = null;
                $identificación = null;
                $cliente = null;                
                $maquina = null;
                $sede = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $fecha = Html::encode($form->fecha);
                        $identificación = Html::encode($form->identificacion);
                        $cliente = Html::encode($form->cliente);
                        $maquina = Html::encode($form->maquina);
                        $sede = Html::encode($form->sede);                        
                        $table = Eventos::find()
                            ->andFilterWhere(['=', 'fechai', $email])
                            ->andFilterWhere(['=', 'identificacion', $identificación])
                            ->andFilterWhere(['like', 'nombres', $nombre1])
                            ->andFilterWhere(['like', 'maquina', $nombre2])
                            ->andFilterWhere(['like', 'sede_fk', $apellido1])   
                            ->orderBy('cliente_pk desc');
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
                    $table = Eventos::find()
                        ->orderBy('id desc');
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
            }else{
                return $this->redirect(["site/login"]);
            }

        }

        public function actionNuevo()
        {
            $model = new FormCliente;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = new Cliente;
                    $table->identificacion = $model->identificacion;
                    $table->nombre1 = $model->nombre1;
                    $table->nombre2 = $model->nombre2;                    
                    $table->apellido1 = $model->apellido1;
                    $table->apellido2 = $model->apellido2;                    
                    $table->telefono = $model->telefono;
                    $table->celular = $model->celular;
                    $table->email = $model->email;
                    $table->direccion = $model->direccion;                                        
                    $table->sede_fk = $model->sede_fk;
                    if ($table->insert()) {
                        $msg = "Registros guardados correctamente";
                        $model->identificacion = null;
                        $model->nombre1 = null;
                        $model->nombre2 = null;                       
                        $model->apellido1 = null;
                        $model->apellido2 = null;                        
                        $model->telefono = null;
                        $model->celular = null;
                        $model->email = null;
                        $model->direccion = null;                        
                        $model->sede_fk = null;
                    } else {
                        $msg = "error";
                    }
                } else {
                    $model->getErrors();
                }
            }

            return $this->render('nuevo', ['model' => $model, 'msg' => $msg, 'tipomsg' => $tipomsg]);
        }

        public function actionEditar()
        {
            $model = new FormCliente();
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = Cliente::find()->where(['cliente_pk' => $model->cliente_pk])->one();
                    if ($table) {
                        $table->identificacion = $model->identificacion;
                        $table->nombre1 = $model->nombre1;
                        $table->nombre2 = $model->nombre2;                        
                        $table->apellido1 = $model->apellido1;
                        $table->apellido2 = $model->apellido2;                        
                        $table->telefono = $model->telefono;
                        $table->celular = $model->celular;
                        $table->email = $model->email;
                        $table->direccion = $model->direccion;                        
                        $table->sede_fk = $model->sede_fk;                        
                        if ($table->update()) {
                            $msg = "El registro ha sido actualizado correctamente";
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
            if (Yii::$app->request->get("cliente_pk")) {
                $cliente_pk = Html::encode($_GET["cliente_pk"]);
                $table = Cliente::find()->where(['cliente_pk' => $cliente_pk])->one();
                if ($table) {
                    $model->cliente_pk = $table->cliente_pk;
                    $model->identificacion = $table->identificacion;
                    $model->nombre1 = $table->nombre1;
                    $model->nombre2 = $table->nombre2;                    
                    $model->apellido1 = $table->apellido1;
                    $model->apellido2 = $table->apellido2;                    
                    $model->telefono = $table->telefono;
                    $model->celular = $table->celular;
                    $model->email = $table->email;
                    $model->direccion = $table->direccion;                    
                    $model->sede_fk = $table->sede_fk;
                } else {
                    return $this->redirect(["cliente/index"]);
                }
            } else {
                return $this->redirect(["cliente/index"]);
            }
            return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
        }                               

}