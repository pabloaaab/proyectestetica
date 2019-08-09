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
use app\models\Cliente;
use app\models\Habeasdata;
use app\models\FormatoAutorizacion;
use app\models\FormHabeasdata;
use yii\helpers\Url;
use app\models\FormFiltroHabeasdata;
use yii\web\UploadedFile;
use app\models\FormFirmaCliente;

    class HabeasdataController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
                $form = new FormFiltroHabeasdata;
                $search = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $search = Html::encode($form->q);
                        $table = Habeasdata::find()
                            ->where(['like', 'id', $search])
                            ->orWhere(['like', 'identificacion', $search])
                            ->orWhere(['like', 'nombre', $search])
                            ->orderBy('id desc');
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
                    $table = Habeasdata::find()
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
                    'search' => $search,
                    'pagination' => $pages,

                ]);
            }else{
                return $this->redirect(["site/login"]);
            }

        }

        public function actionNuevo()
        {
            $model = new Habeasdata;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = new Habeasdata;
                    $nombrecliente = Cliente::find()->where(['=','identificacion',$model->identificacion])->one();
                    $table->identificacion = $model->identificacion;
                    $table->nombre = $nombrecliente->nombre1.' '.$nombrecliente->nombre2.' '.$nombrecliente->apellido1.' '.$nombrecliente->apellido2;
                    $table->sede_fk = $model->sede_fk;                                        
                    $table->autorizacion = $model->autorizacion;
                    $table->fechaautorizacion = $model->fechaautorizacion;
                    $table->fecha_creacion = date('Y-m-d');
                    if ($table->insert()) {
                        $msg = "Registros guardados correctamente";
                        $model->identificacion = null;
                        $model->nombre = null;
                        $model->sede_fk = null;
                        $model->autorizacion = null;                                                
                        $model->fechaautorizacion = null;
                        
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
            $model = new Habeasdata;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = Habeasdata::find()->where(['id' => $model->id])->one();
                    $nombrecliente = Cliente::find()->where(['=','identificacion',$model->identificacion])->one();
                    if ($table) {
                        $table->identificacion = $model->identificacion;
                        $table->nombre = $nombrecliente->nombre1.' '.$nombrecliente->nombre2.' '.$nombrecliente->apellido1.' '.$nombrecliente->apellido2;
                        $table->sede_fk = $model->sede_fk;                                        
                        $table->autorizacion = $model->autorizacion;
                        $table->fechaautorizacion = $model->fechaautorizacion;
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
                $table = Habeasdata::find()->where(['id' => $id])->one();                
                if ($table) {
                    $model->id = $table->id;
                    $model->identificacion = $table->identificacion;                    
                    $model->sede_fk = $table->sede_fk;                    
                    $model->autorizacion = $table->autorizacion;
                    $model->fechaautorizacion = $table->fechaautorizacion;                    
                } else {
                    return $this->redirect(["habeasdata/index"]);
                }
            } else {
                return $this->redirect(["habeasdata/index"]);
            }
            return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
        }

        public function actionFirma_cliente()
        {
            $model = new FormFirmaCliente();
            $msg = null;
            $id = Html::encode($_GET["id"]);

            if (Yii::$app->request->isPost) {
                $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
                if ($model->upload()) {
                    $table = Habeasdata::find()->where(['id' => $id])->one();                    
                    if ($table) {
                        $table->firma = $model->imageFile;
                        $table->save(false);
                        return $this->redirect(["habeasdata/index"]);
                        // el archivo se subió exitosamente
                    } else {
                        $msg = "El registro seleccionado no ha sido encontrado";
                    }                       
                }
            }
            
            return $this->render("firmaCliente", ["model" => $model, "msg" => $msg]);
        }
              
        public function actionImprimir($id) {
            $formato = FormatoAutorizacion::findOne(1);
            $model = Habeasdata::find()->where(['id' => $id])->one();
            return $this->render("generarimprimir", ["model" => $model, 'formato' => $formato]);
        }

}