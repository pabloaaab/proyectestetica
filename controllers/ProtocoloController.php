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
use app\models\Protocolo;
use app\models\ConsentimientoCliente;
use app\models\FormatoAutorizacion;
use yii\helpers\Url;
use app\models\FormFiltroProtocolo;
use yii\web\UploadedFile;
use app\models\FormFirmaCliente;

    class ProtocoloController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
                $form = new FormFiltroProtocolo;
                $search = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $search = Html::encode($form->q);
                        $table = ConsentimientoCliente::find()
                            ->where(['like', 'consentimiento_cliente_pk', $search])
                            ->orWhere(['like', 'identificacion', $search])
                            ->orWhere(['like', 'nombre', $search])
                            ->orderBy('consentimiento_cliente_pk desc');
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
                    $table = ConsentimientoCliente::find()
                        ->orderBy('consentimiento_cliente_pk desc');
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
            $model = new ConsentimientoCliente;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = new ConsentimientoCliente;                    
                    $table->identificacion = $model->identificacion;
                    $table->nombre = $model->nombre;
                    $table->sede_fk = $model->sede_fk;                                        
                    $table->consentimiento = $model->consentimiento;
                    $table->fechaconsentimiento = $model->fechaconsentimiento;
                    $table->fecha_creacion = date('Y-m-d');
                    $table->fototipo_area = $model->fototipo_area;
                    $table->fototipo_piel = $model->fototipo_piel;
                    if ($table->insert()) {
                        $msg = "Registros guardados correctamente";
                        $model->identificacion = null;
                        $model->nombre = null;
                        $model->sede_fk = null;
                        $model->consentimiento = null;                                                
                        $model->fechaconsentimiento = null;
                        $model->fecha_creacion = null;
                        $model->fototipo_area = null;
                        $model->fototipo_piel = null;
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
            $model = new ConsentimientoCliente;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = ConsentimientoCliente::find()->where(['consentimiento_cliente_pk' => $model->consentimiento_cliente_pk])->one();                    
                    if ($table) {
                        $table->identificacion = $model->identificacion;
                        $table->nombre = $model->nombre;
                        $table->sede_fk = $model->sede_fk;                                        
                        $table->consentimiento = $model->consentimiento;
                        $table->fechaconsentimiento = $model->fechaconsentimiento;
                        $table->fecha_modificacion = date('Y-m-d');
                        $table->fototipo_area = $model->fototipo_area;
                        $table->fototipo_piel = $model->fototipo_piel;
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
                $consentimiento_cliente_pk = Html::encode($_GET["id"]);
                $table = ConsentimientoCliente::find()->where(['consentimiento_cliente_pk' => $consentimiento_cliente_pk])->one();                
                if ($table) {
                    $model->consentimiento_cliente_pk = $table->consentimiento_cliente_pk;
                    $model->identificacion = $table->identificacion;
                    $model->nombre = $table->nombre;
                    $model->sede_fk = $table->sede_fk;                                        
                    $model->consentimiento = $table->consentimiento;
                    $model->fechaconsentimiento = $table->fechaconsentimiento;                    
                    $model->fototipo_area = $table->fototipo_area;
                    $model->fototipo_piel = $table->fototipo_piel;                    
                } else {
                    return $this->redirect(["protocolo/index"]);
                }
            } else {
                return $this->redirect(["protocolo/index"]);
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
                    $table = ConsentimientoCliente::find()->where(['consentimiento_cliente_pk' => $id])->one();                    
                    if ($table) {
                        $table->firma = $model->imageFile;
                        $table->save(false);
                        return $this->redirect(["protocolo/index"]);
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