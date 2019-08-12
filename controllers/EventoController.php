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
                        $table = Eventos::find()
                            ->andFilterWhere(['like', 'fechai', $fecha])
                            ->andFilterWhere(['=', 'identificacion', $identificación])
                            ->andFilterWhere(['like', 'nombres', $cliente])
                            ->andFilterWhere(['like', 'maquina', $maquina])
                            ->andFilterWhere(['like', 'sede_fk', $sede])   
                            ->orderBy('fechai desc');
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
                        ->orderBy('fechai desc');
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
            }else{
                $evento->cancelo_no_asistio = 0;                
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
                 $events = Eventos::find()->all();
                foreach ($events as $event){
                    $event = new Eventos;
                    $event->id = 1;
                    $event->asunto = 'Testing';
                    $event->fechai = date('Y-m-d\Th:m:s\Z');
                    $events[] = $event;
                }
                 
                return $this->render('indexevento', [
                    'events' => $events,                                        
                ]);
            }else{
                return $this->redirect(["site/login"]);
            }

        }
                
        public function actionForm($start,$end)
        {
            return $this->renderAjax('form',[
                'start'=>$start,
                'end'=>$end
            ]);
        }

        public function actionDropChild($id,$start,$end){
            echo "ID=".$id." START=".$start." EBD=".$end;
            //$model = Pilotproject::findOne(['ID'=>$id]);

            //$model->PLAN_DATE1 = $start;
            //$model->PLAN_DATE2 = $end;

           // $model->save();
        }

        public function actionEventCalendarSchedule()
        {
            $aryEvent=[
                    ['id' => '1', 'resourceId' => 'b', 'start' => '2016-05-07T02:00:00', 'end' => '2016-05-07T07:00:00', 'title' => 'event 1'],
                    ['id' => '2', 'resourceId' => 'c', 'start' => '2016-05-07T05:00:00', 'end' => '2016-05-07T22:00:00', 'title' => 'event 2'],
                    ['id' => '3', 'resourceId' => 'd', 'start' => '2016-05-06', 'end' => '2016-05-08', 'title' => 'event 3'],
                    ['id' => '4', 'resourceId' => 'e', 'start' => '2016-05-07T03:00:00', 'end' => '2016-05-07T08:00:00', 'title' => 'event 4'],
                    ['id' => '5', 'resourceId' => 'f', 'start' => '2016-05-07T00:30:00', 'end' => '2016-05-07T02:30:00', 'title' => 'event 5'],
            ];

            return Json::encode($aryEvent);
        }

        public function actionResourceCalendarSchedule()
        {
            $aryResource=[
                    ['id' => 'a', 'title' => 'Daily Report'],
                    ['id' => 'b', 'title' => 'Auditorium B', 'eventColor' => 'green'],
                    ['id' => 'c', 'title' => 'Auditorium C', 'eventColor' => 'orange'],
                    [
                        'id'       => 'd', 'title' => 'Auditorium D',
                        'children' => [
                            ['id' => 'd1', 'title' => 'Room D1'],
                            ['id' => 'd2', 'title' => 'Room D2'],
                        ],
                    ],
                    ['id' => 'e', 'title' => 'Auditorium E'],
                    ['id' => 'f', 'title' => 'Auditorium F', 'eventColor' => 'red'],
                    ['id' => 'g', 'title' => 'Auditorium G'],
                    ['id' => 'h', 'title' => 'Auditorium H'],
                    ['id' => 'i', 'title' => 'Auditorium I'],
                    ['id' => 'j', 'title' => 'Auditorium J'],
                    ['id' => 'k', 'title' => 'Auditorium K'],
                    ['id' => 'l', 'title' => 'Auditorium L'],
                    ['id' => 'm', 'title' => 'Auditorium M'],
                    ['id' => 'n', 'title' => 'Auditorium N'],
                    ['id' => 'o', 'title' => 'Auditorium O'],
                    ['id' => 'p', 'title' => 'Auditorium P'],
                    ['id' => 'q', 'title' => 'Auditorium Q'],
                    ['id' => 'r', 'title' => 'Auditorium R'],
                    ['id' => 's', 'title' => 'Auditorium S'],
                    ['id' => 't', 'title' => 'Auditorium T'],
                    ['id' => 'u', 'title' => 'Auditorium U'],
                    ['id' => 'v', 'title' => 'Auditorium V'],
                    ['id' => 'w', 'title' => 'Auditorium W'],
                    ['id' => 'x', 'title' => 'Auditorium X'],
                    ['id' => 'y', 'title' => 'Auditorium Y'],
                    ['id' => 'z', 'title' => 'Auditorium Z'],
                ];

            return Json::encode($aryResource);
        }

}