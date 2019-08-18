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
use app\models\FormFiltroEventoAgenda;
use yii\web\UploadedFile;

    class EventoController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
                $usuario = \app\models\Users::find()->where(['=','id',Yii::$app->user->identity->id])->one();
                $usuarioperfil = $usuario->role;
                $usuariosede = $usuario->sede_fk;
                $form = new FormFiltroEvento;
                $fecha = null;
                $identificación = null;
                $cliente = null;                
                $maquina = null;
                $sede = null;
                $anio_mes_dia = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $fecha = Html::encode($form->fecha);
                        $identificación = Html::encode($form->identificacion);
                        $cliente = Html::encode($form->cliente);
                        $maquina = Html::encode($form->maquina);
                        $sede = Html::encode($form->sede_fk);
                        $anio_mes_dia = Html::encode($form->anio_mes_dia);
                        if ($anio_mes_dia == "dia"){
                            $fecha = $fecha;
                        }
                        if ($anio_mes_dia == "mes"){
                            $fecha = date('Y-m', strtotime($fecha));
                        }
                        if ($anio_mes_dia == "anio"){
                            $fecha = date('Y', strtotime($fecha));
                        }
                        if ($usuarioperfil == 2) { //administrador
                            $table = Eventos::find()
                            ->andFilterWhere(['like', 'fechai', $fecha])
                            ->andFilterWhere(['=', 'identificacion', $identificación])
                            ->andFilterWhere(['like', 'nombres', $cliente])
                            ->andFilterWhere(['like', 'maquina', $maquina])
                            ->andFilterWhere(['like', 'sede_fk', $sede])   
                            ->orderBy('fechai desc');
                        }else{//administrativo
                            $table = Eventos::find()
                            ->where(['=', 'sede_fk', $usuariosede])
                            ->andFilterWhere(['like', 'fechai', $fecha])
                            ->andFilterWhere(['=', 'identificacion', $identificación])
                            ->andFilterWhere(['like', 'nombres', $cliente])
                            ->andFilterWhere(['like', 'maquina', $maquina])
                            ->andFilterWhere(['like', 'sede_fk', $sede])   
                            ->orderBy('fechai desc');
                        }
                        
                        $count = clone $table;
                        $pages = new Pagination([
                            'pageSize' => 35,
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
                    if ($usuarioperfil == 2) { //administrador
                        $table = Eventos::find()                           
                        ->orderBy('fechai desc');
                    }else{//administrativo
                        $table = Eventos::find()
                        ->where(['=', 'sede_fk', $usuariosede])                           
                        ->orderBy('fechai desc');
                    }
                    $count = clone $table;
                    $pages = new Pagination([
                        'pageSize' => 35,
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
            $model = new FormEvento();
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;                
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = new Eventos();
                    $table->identificacion = $model->identificacion;
                    $cliente = \app\models\Cliente::find()->where(['=','identificacion',$model->identificacion])->one();
                    $table->nombres = $cliente->nombrecompletosinidentificacion;
                    $table->asunto = $model->asunto;                    
                    $table->fechai = date("Y-m-d",strtotime($model->fechai)).' '.$model->horai;
                    $fecha = $table->fechai;
                    $minutos = Maquina::find()->where(['=','id_maquina',$model->maquina])->one();
                    $minutos = '+'.$minutos->duracion.' minute';
                    $nuevafecha = strtotime ( $minutos , strtotime ( $fecha ) ) ;                    
                    $nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha );
                    $table->fechat = $nuevafecha;
                    $table->id_profesional = $model->id_profesional;                    
                    $table->telefono = $model->telefono;
                    $table->maquina = $model->maquina;                                                                               
                    $table->sede_fk = $model->sede_fk;
                    $table->observaciones = $model->observaciones;
                    if ($table->insert()) {
                        $msg = "Registros guardados correctamente";
                        $model->identificacion = null;                        
                        $model->asunto = null;                       
                        $model->fechai = null;
                        $model->horai = null;
                        $model->id_profesional = null;                        
                        $model->telefono = null;
                        $model->maquina = null;
                        $model->sede_fk = null;
                        $model->observaciones = null;                        
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
            $model = new FormEvento();
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = Eventos::find()->where(['id' => $model->id])->one();
                    if ($table) {
                        $table->identificacion = $model->identificacion;
                        $cliente = \app\models\Cliente::find()->where(['=','identificacion',$model->identificacion])->one();
                        $table->nombres = $cliente->nombrecompletosinidentificacion;
                        $table->asunto = $model->asunto;                    
                        $table->fechai = date("Y-m-d",strtotime($model->fechai)).' '.$model->horai;
                        $fecha = $table->fechai;
                        $minutos = Maquina::find()->where(['=','id_maquina',$model->maquina])->one();
                        $minutos = '+'.$minutos->duracion.' minute';
                        $nuevafecha = strtotime ( $minutos , strtotime ( $fecha ) ) ;                    
                        $nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha );
                        $table->fechat = $nuevafecha;
                        $table->id_profesional = $model->id_profesional;                    
                        $table->telefono = $model->telefono;
                        $table->maquina = $model->maquina;                                                                               
                        $table->sede_fk = $model->sede_fk;
                        $table->observaciones = $model->observaciones;                       
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
            if (Yii::$app->request->get("id")) {
                $id = Html::encode($_GET["id"]);
                $table = Eventos::find()->where(['id' => $id])->one();
                if ($table) {
                    $model->id = $table->id;
                    $model->asunto = $table->asunto;
                    $model->identificacion = $table->identificacion;                                        
                    $model->maquina = $table->maquina;
                    $model->fechai = date("Y-m-d",strtotime($table->fechai));
                    $model->horai = date("H:i:s",strtotime($table->fechai));
                    $model->telefono = $table->telefono;
                    $model->id_profesional = $table->id_profesional;
                    $model->observaciones = $table->observaciones;                    
                    $model->sede_fk = $table->sede_fk;
                } else {
                    return $this->redirect(["evento/index"]);
                }
            } else {
                return $this->redirect(["evento/index"]);
            }
            return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
        }
        
        public function actionCancelar($id) {
        if (Yii::$app->request->get()) {
            $evento = Eventos::findOne($id);
            if ($evento->cancelo_no_asistio == 0){
                $evento->cancelo_no_asistio = 1;
                $evento->color = "yellow";
            }else{
                $evento->cancelo_no_asistio = 0;
                $evento->color = "";                
            }            
            $evento->save(false);
            $this->redirect(["evento/index"]);
        } else {
            $this->redirect(["evento/index"]);
        }
    }
    
        public function actionIndexevento()
        {
            if (!Yii::$app->user->isGuest) {
                $usuario = \app\models\Users::find()->where(['=','id',Yii::$app->user->identity->id])->one();
                $usuarioperfil = $usuario->role;
                $usuariosede = $usuario->sede_fk;
                $form = new FormFiltroEventoAgenda();
                $sede_fk = null;
                $maquina = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $maquina = Html::encode($form->maquina);
                        $sede_fk = Html::encode($form->sede_fk);
                        $events = Eventos::find()                            
                            ->andFilterWhere(['like', 'maquina', $maquina])
                            ->andFilterWhere(['like', 'sede_fk', $sede_fk])
                            ->all();
                       
                        return $this->render('indexevento', [
                        'events' => $events,                        
                        'form' => $form,
                    ]); 
                    } else {
                        $form->getErrors();
                    }
                }else{
                    if ($usuarioperfil == 2) { //administrador
                        $events = Eventos::find()->all();
                    }else{ //administrativo
                        $events = Eventos::find()->where(['=','sede_fk',$usuariosede])->all();
                    }                                    

                    return $this->render('indexevento', [
                        'events' => $events,                        
                        'form' => $form,
                    ]);                    
                }
                    
                
            }else{
                return $this->redirect(["site/login"]);
            }

        }                                

}