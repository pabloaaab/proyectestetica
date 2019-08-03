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
use yii\web\UploadedFile;

    class ClienteController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
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
                    $table = Cliente::find()
                        ->orderBy('cliente_pk desc');
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
            $model = new FormInscrito;
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
                    $table->tipo_doc = $model->tipo_doc;
                    $table->apellido1 = $model->apellido1;
                    $table->apellido2 = $model->apellido2;
                    $table->nom_madre = $model->nom_madre;
                    $table->nom_padre = $model->nom_padre;
                    $table->doc_madre = $model->doc_madre;
                    $table->doc_padre = $model->doc_padre;
                    $table->ocupacion_madre = $model->ocupacion_madre;
                    $table->ocupacion_padre = $model->ocupacion_padre;
                    $table->tipo_personal = $model->tipo_personal;
                    $table->lugar_exp = $model->lugar_exp;
                    $table->telefono = $model->telefono;
                    $table->celular = $model->celular;
                    $table->email = $model->email;
                    $table->direccion = $model->direccion;
                    $table->sexo = $model->sexo;
                    $table->comuna = $model->comuna;
                    $table->barrio = $model->barrio;
                    $table->fecha_nac = $model->fecha_nac;
                    $table->municipio_nac = $model->municipio_nac;
                    $table->departamento_nac = $model->departamento_nac;
                    $table->municipio = $model->municipio;
                    $table->estudio1 = $model->estudio1;
                    $table->estudio2 = $model->estudio2;
                    $table->gradoc1 = $model->gradoc1;
                    $table->gradoc2 = $model->gradoc2;
                    $table->anioc1 = $model->anioc1;
                    $table->anioc2 = $model->anioc2;
                    $table->graduado1 = $model->graduado1;
                    $table->graduado2 = $model->graduado2;
                    $table->autoriza = $model->autoriza;
                    $table->fecha_autoriza = $model->fecha_autoriza;
                    $table->ciudad_firma = $model->ciudad_firma;
                    if ($table->insert()) {
                        $msg = "Registros guardados correctamente";
                        $model->identificacion = null;
                        $model->nombre1 = null;
                        $model->nombre2 = null;
                        $model->tipo_doc = null;
                        $model->apellido1 = null;
                        $model->apellido2 = null;
                        $model->nom_madre = null;
                        $model->nom_padre = null;
                        $model->doc_madre = null;
                        $model->doc_padre = null;
                        $model->ocupacion_madre = null;
                        $model->ocupacion_padre = null;
                        $model->tipo_personal = null;
                        $model->lugar_exp = null;
                        $model->telefono = null;
                        $model->celular = null;
                        $model->email = null;
                        $model->direccion = null;
                        $model->sexo = null;
                        $model->comuna = null;
                        $model->barrio = null;
                        $model->fecha_nac = null;
                        $model->municipio_nac = null;
                        $model->departamento_nac = null;
                        $model->municipio = null;
                        $model->estudio1 = null;
                        $model->estudio2 = null;
                        $model->gradoc1 = null;
                        $model->gradoc2 = null;
                        $model->anioc1 = null;
                        $model->anioc2 = null;
                        $model->graduado1 = null;
                        $model->graduado2 = null;
                        $model->autoriza = null;
                        $model->fecha_autoriza = null;
                        $model->ciudad_firma = null;
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
            $model = new FormInscrito;
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
                        $table->tipo_doc = $model->tipo_doc;
                        $table->apellido1 = $model->apellido1;
                        $table->apellido2 = $model->apellido2;
                        $table->nom_madre = $model->nom_madre;
                        $table->nom_padre = $model->nom_padre;
                        $table->doc_madre = $model->doc_madre;
                        $table->doc_padre = $model->doc_padre;
                        $table->ocupacion_madre = $model->ocupacion_madre;
                        $table->ocupacion_padre = $model->ocupacion_padre;
                        $table->tipo_personal = $model->tipo_personal;
                        $table->lugar_exp = $model->lugar_exp;
                        $table->telefono = $model->telefono;
                        $table->celular = $model->celular;
                        $table->email = $model->email;
                        $table->direccion = $model->direccion;
                        $table->sexo = $model->sexo;
                        $table->comuna = $model->comuna;
                        $table->barrio = $model->barrio;
                        $table->fecha_nac = $model->fecha_nac;
                        $table->municipio_nac = $model->municipio_nac;
                        $table->departamento_nac = $model->departamento_nac;
                        $table->municipio = $model->municipio;
                        $table->estudio1 = $model->estudio1;
                        $table->estudio2 = $model->estudio2;
                        $table->gradoc1 = $model->gradoc1;
                        $table->gradoc2 = $model->gradoc2;
                        $table->anioc1 = $model->anioc1;
                        $table->anioc2 = $model->anioc2;
                        $table->graduado1 = $model->graduado1;
                        $table->graduado2 = $model->graduado2;
                        $table->autoriza = $model->autoriza;
                        $table->fecha_autoriza = $model->fecha_autoriza;
                        $table->ciudad_firma = $model->ciudad_firma;
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
                    $model->tipo_doc = $table->tipo_doc;
                    $model->apellido1 = $table->apellido1;
                    $model->apellido2 = $table->apellido2;
                    $model->nom_madre = $table->nom_madre;
                    $model->nom_padre = $table->nom_padre;
                    $model->doc_madre = $table->doc_madre;
                    $model->doc_padre = $table->doc_padre;
                    $model->ocupacion_madre = $table->ocupacion_madre;
                    $model->ocupacion_padre = $table->ocupacion_padre;
                    $model->tipo_personal = $table->tipo_personal;
                    $model->lugar_exp = $table->lugar_exp;
                    $model->telefono = $table->telefono;
                    $model->celular = $table->celular;
                    $model->email = $table->email;
                    $model->direccion = $table->direccion;
                    $model->sexo = $table->sexo;
                    $model->comuna = $table->comuna;
                    $model->barrio = $table->barrio;
                    $model->fecha_nac = $table->fecha_nac;
                    $model->municipio_nac = $table->municipio_nac;
                    $model->departamento_nac = $table->departamento_nac;
                    $model->municipio = $table->municipio;
                    $model->estudio1 = $table->estudio1;
                    $model->estudio2 = $table->estudio2;
                    $model->gradoc1 = $table->gradoc1;
                    $model->gradoc2 = $table->gradoc2;
                    $model->anioc1 = $table->anioc1;
                    $model->anioc2 = $table->anioc2;
                    $model->graduado1 = $table->graduado1;
                    $model->graduado2 = $table->graduado2;
                    $model->autoriza = $table->autoriza;
                    $model->fecha_autoriza = $table->fecha_autoriza;
                    $model->ciudad_firma = $table->ciudad_firma;
                } else {
                    return $this->redirect(["inscritos/index"]);
                }
            } else {
                return $this->redirect(["inscritos/index"]);
            }
            return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
        }                               

}