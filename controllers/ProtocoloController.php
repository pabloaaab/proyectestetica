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
use app\models\FormProtocoloNuevo;

    class ProtocoloController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
                $usuario = \app\models\Users::find()->where(['=','id',Yii::$app->user->identity->id])->one();
                $usuarioperfil = $usuario->role;
                $usuariosede = $usuario->sede_fk; 
                $form = new FormFiltroProtocolo;
                $search = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $search = Html::encode($form->q);
                        if ($usuarioperfil == 2) { //administrador
                            $table = ConsentimientoCliente::find()
                                ->andFilterWhere(['=', 'identificacion', $search])
                                ->orderBy('consentimiento_cliente_pk desc');
                        }else{
                            $table = ConsentimientoCliente::find()
                                ->where(['=','sede_fk',$usuariosede])    
                                ->andFilterWhere(['=', 'identificacion', $search])
                                ->orderBy('consentimiento_cliente_pk desc');
                        }
                            
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
                    if ($usuarioperfil == 2) { //administrador
                        $table = ConsentimientoCliente::find()
                        ->orderBy('consentimiento_cliente_pk desc');
                    }else{ //administrativo
                        $table = ConsentimientoCliente::find()
                        ->where(['=','sede_fk',$usuariosede])
                        ->orderBy('consentimiento_cliente_pk desc');
                    }
                    
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
        
        public function actionVista($id)
    {
        $model = ConsentimientoCliente::findOne($id);
        $protocolos = Protocolo::find()->Where(['=', 'consentimiento_cliente_fk', $id])->all();        
        $mensaje = "";                        
        return $this->render('vista', [
            'model' => $model,            
            'protocolos' => $protocolos,
            'mensaje' => $mensaje,
        ]);
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
        
        public function actionNuevoprotocolo($id) {
            $model = new FormProtocoloNuevo();        
            if ($model->load(Yii::$app->request->post())) {                                    
                $table = new Protocolo();            
                $table->fecha = $model->fecha;
                $table->pieza_mano = $model->pieza_mano;
                $table->potencia_tiempo = $model->potencia_tiempo;
                $table->energia = $model->energia;
                $table->area = $model->area;
                $table->pases = $model->pases;
                $table->consentimiento_cliente_fk = $id;
                $table->save(false);                

                return $this->redirect(['vista','id' => $id]);
            }
            return $this->renderAjax('nuevoprotocolo', [
                'model' => $model,            
            ]);        
        }
        
        public function actionEditarprotocolo()
    {
        $id_protocolo = Html::encode($_POST["iddetalle"]);
        $consentimiento_cliente_pk = Html::encode($_POST["consentimiento_cliente_pk"]);
        if(Yii::$app->request->post()){
            if((int) $id_protocolo)
            {
                $table = Protocolo::findOne($id_protocolo);
                if ($table) {
                    $table->fecha = Html::encode($_POST["fecha"]);
                    $table->pieza_mano = Html::encode($_POST["pieza_mano"]);
                    $table->potencia_tiempo = Html::encode($_POST["potencia_tiempo"]);
                    $table->area = Html::encode($_POST["area"]);
                    $table->energia = Html::encode($_POST["energia"]);
                    $table->pases = Html::encode($_POST["pases"]);
                    $table->save(false);                                        
                    $this->redirect(["protocolo/vista",'id' => $consentimiento_cliente_pk]);

                } else {
                    $msg = "El registro seleccionado no ha sido encontrado";
                    $tipomsg = "danger";
                }
            }
        }
        //return $this->render("_formeditardetalle", ["model" => $model,]);
    }

        
        public function actionEliminarprotocolo()
        {
            if(Yii::$app->request->post())
            {
                $id_protocolo = Html::encode($_POST["iddetalle"]);
                $consentimiento_cliente_pk = Html::encode($_POST["consentimiento_cliente_pk"]);
                if((int) $id_protocolo)
                {
                    $protocolo = Protocolo::findOne($id_protocolo);                
                    if(Protocolo::deleteAll("id_protocolo=:id_protocolo", [":id_protocolo" => $id_protocolo]))
                    {                                                                                
                        $this->redirect(["protocolo/vista",'id' => $consentimiento_cliente_pk]);
                    }
                    else
                    {
                        echo "<meta http-equiv='refresh' content='3; ".Url::toRoute("protocolo/index")."'>";
                    }
                }
                else
                {
                    echo "<meta http-equiv='refresh' content='3; ".Url::toRoute("protocolo/index")."'>";
                }
            }
            else
            {
                return $this->redirect(["protocolo/index"]);
            }
        }
              
        public function actionImprimirconsentimiento($id) {
            $formato = \app\models\Consentimiento::findOne(1);
            $model = ConsentimientoCliente::find()->where(['consentimiento_cliente_pk' => $id])->one();
            return $this->render("generarimprimirconsentimiento", ["model" => $model, 'formato' => $formato]);
        }
        
        public function actionImprimirprotocolo($id) {            
            $model = ConsentimientoCliente::find()->where(['consentimiento_cliente_pk' => $id])->one();
            return $this->render("generarimprimirprotocolo", ["model" => $model]);
        }                

}