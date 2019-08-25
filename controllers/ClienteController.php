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
use app\models\FormCliente;
use yii\helpers\Url;
use app\models\FormFiltroCliente;
use app\models\FormFiltroFechaCliente;
use yii\web\UploadedFile;

    class ClienteController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
                $usuario = \app\models\Users::find()->where(['=','id',Yii::$app->user->identity->id])->one();
                $usuarioperfil = $usuario->role;
                $usuariosede = $usuario->sede_fk;                
                $form = new FormFiltroCliente;
                $email = null;
                $identificación = null;
                $nombre1 = null;
                $nombre2 = null;
                $apellido1 = null;
                $apellido2 = null;
                $telefono = null;
                $celular = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $email = Html::encode($form->email);
                        $identificación = Html::encode($form->identificacion);
                        $nombre1 = Html::encode($form->nombre1);
                        $nombre2 = Html::encode($form->nombre2);
                        $apellido1 = Html::encode($form->apellido1);
                        $apellido2 = Html::encode($form->apellido2);
                        $telefono = Html::encode($form->telefono);
                        $celular = Html::encode($form->celular);
                        if ($usuarioperfil == 2) { //administrador
                            $table = Cliente::find()
                            ->andFilterWhere(['=', 'email', $email])
                            ->andFilterWhere(['=', 'identificacion', $identificación])
                            ->andFilterWhere(['like', 'nombre1', $nombre1])
                            ->andFilterWhere(['like', 'nombre2', $nombre2])
                            ->andFilterWhere(['like', 'apellido1', $apellido1])
                            ->andFilterWhere(['like', 'apellido2', $apellido2])
                            ->andFilterWhere(['=', 'telefono', $telefono])
                            ->andFilterWhere(['=', 'celular', $celular])    
                            ->orderBy('cliente_pk desc');
                        }else{ //administrativo
                            $table = Cliente::find()
                            ->where(['=','sede_fk',$usuariosede])        
                            ->andFilterWhere(['=', 'email', $email])
                            ->andFilterWhere(['=', 'identificacion', $identificación])
                            ->andFilterWhere(['like', 'nombre1', $nombre1])
                            ->andFilterWhere(['like', 'nombre2', $nombre2])
                            ->andFilterWhere(['like', 'apellido1', $apellido1])
                            ->andFilterWhere(['like', 'apellido2', $apellido2])
                            ->andFilterWhere(['=', 'telefono', $telefono])
                            ->andFilterWhere(['=', 'celular', $celular])    
                            ->orderBy('cliente_pk desc');
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
                        $table = Cliente::find()
                        ->orderBy('cliente_pk desc');
                    }else{ //administrativo
                        $table = Cliente::find()
                        ->where(['=','sede_fk',$usuariosede])        
                        ->orderBy('cliente_pk desc');
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
                    'pagination' => $pages,

                ]);
            }else{
                return $this->redirect(["site/login"]);
            }

        }
        
        public function actionIngresos()
        {
            if (!Yii::$app->user->isGuest) {                
                $form = new FormFiltroFechaCliente;
                $fecha = null;
                $sede_fk = null;                
                $anio_mes_dia = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $fechacreacion = Html::encode($form->fechacreacion);
                        $sede_fk = Html::encode($form->sede_fk);
                        $anio_mes_dia = Html::encode($form->anio_mes_dia);
                        if ($anio_mes_dia == "dia"){
                            $fechacreacion = $fechacreacion;
                        }
                        if ($anio_mes_dia == "mes"){
                            $fechacreacion = date('Y-m', strtotime($fechacreacion));
                        }
                        if ($anio_mes_dia == "anio"){
                            $fechacreacion = date('Y', strtotime($fechacreacion));
                        }                        
                        $table = Cliente::find()
                            ->andFilterWhere(['like', 'fechacreacion', $fechacreacion])
                            ->andFilterWhere(['like', 'sede_fk', $sede_fk])
                            ->orderBy('fechacreacion desc');                                                
                        $count = clone $table;
                        $pages = new Pagination([
                            'pageSize' => 50,
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
                    $table = Cliente::find()
                        ->Where(['=','cliente_pk',0])    
                        ->orderBy('fechacreacion desc');                    
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
                return $this->render('ingresos', [
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
                    $table->fechacreacion = date('Y-m-d H:i:s');
                    $table->ultimafechaseccion = date('Y-m-d H:i:s');
                    $table->ultimafechaseccionf = date('Y-m-d H:i:s');
                    if ($table->save(false)) {
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