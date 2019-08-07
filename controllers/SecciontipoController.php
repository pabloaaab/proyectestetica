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
use app\models\SeccionTipo;
use app\models\FormSeccionTipo;
use yii\helpers\Url;
use app\models\FormFiltroSeccionTipo;


    class SecciontipoController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
                $form = new FormFiltroSeccionTipo;
                $search = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $search = Html::encode($form->q);
                        $table = SeccionTipo::find()
                            ->where(['like', 'seccion_tipo_pk', $search])
                            ->andWhere(['=','estado','0'])    
                            ->orWhere(['like', 'tipo', $search])
                            ->orderBy('seccion_tipo_pk asc');
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
                    $table = SeccionTipo::find()
                        ->where(['=','estado','0'])
                        ->orderBy('seccion_tipo_pk asc');
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
            $model = new FormSeccionTipo();
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = new SeccionTipo;                   
                    $table->tipo = $model->tipo;
                    if ($table->save(false)) {
                        $msg = "Registros guardados correctamente";
                        $model->tipo = null;
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
            $model = new FormSeccionTipo;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = SeccionTipo::find()->where(['seccion_tipo_pk' => $model->seccion_tipo_pk])->one();
                    if ($table) {
                        $table->seccion_tipo_pk = $model->seccion_tipo_pk;
                        $table->tipo = $model->tipo;
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

            if (Yii::$app->request->get("seccion_tipo_pk")) {
                $seccion_tipo_pk = Html::encode($_GET["seccion_tipo_pk"]);
                $table = SeccionTipo::find()->where(['seccion_tipo_pk' => $seccion_tipo_pk])->one();
                if ($table) {
                    $model->seccion_tipo_pk = $table->seccion_tipo_pk;
                    $model->tipo = $table->tipo;
                } else {
                    return $this->redirect(["seccionTipo/index"]);
                }
            } else {
                return $this->redirect(["seccionTipo/index"]);
            }
            return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);
        }
}