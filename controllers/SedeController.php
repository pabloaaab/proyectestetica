<?php 

namespace app\controllers;

use yii;

use Codeception\Lib\HelperModule;

use yii\base\Model;

use yii\web\Controller;

use yii\web\Response;

use yii\web\Session;

use yii\data\Pagination;

use yii\filters\AccessControl;

use yii\helpers\Html;

use yii\widgets\ActiveForm;

use app\models\Sedes;

use app\models\FormSede;

use yii\helpers\Url;

use app\models\FormFiltroSede;

class SedeController extends Controller
{



        public function actionIndex()

        {

            if (!Yii::$app->user->isGuest) {

                $form = new FormFiltroSede;

                $search = null;

                if ($form->load(Yii::$app->request->get())) {

                    if ($form->validate()) {

                        $search = Html::encode($form->q);

                        $table = Sedes::find()

                            ->where(['like', 'sede_pk', $search])

                            ->andWhere(['=','estado','0'])    

                            ->orWhere(['like', 'sede', $search])

                            ->orderBy('sede_pk asc');

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

                    $table = Sedes::find()

                        ->where(['=','estado','0'])

                        ->orderBy('sede_pk asc');

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

            $model = new FormSede;

            $msg = null;

            $tipomsg = null;

            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {

                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);

            }

            if ($model->load(Yii::$app->request->post())) {

                if ($model->validate()) {

                    $table = new Sedes;                   

                    $table->sede = $model->sede;

                    if ($table->save(false)) {

                        $msg = "Registros guardados correctamente";

                        $model->sede = null;

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

            $model = new FormSede;

            $msg = null;

            $tipomsg = null;

            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {

                Yii::$app->response->format = Response::FORMAT_JSON;

                return ActiveForm::validate($model);

            }

            if ($model->load(Yii::$app->request->post())) {

                if ($model->validate()) {

                    $table = Sedes::find()->where(['sede_pk' => $model->sede_pk])->one();

                    if ($table) {

                        $table->sede_pk = $model->sede_pk;

                        $table->sede = $model->sede;

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



            if (Yii::$app->request->get("sede_pk")) {

                $sede_pk = Html::encode($_GET["sede_pk"]);

                $table = Sedes::find()->where(['sede_pk' => $sede_pk])->one();

                if ($table) {

                    $model->sede_pk = $table->sede_pk;

                    $model->sede = $table->sede;

                } else {

                    return $this->redirect(["sede/index"]);

                }

            } else {

                return $this->redirect(["sede/index"]);

            }

            return $this->render("editar", ["model" => $model, "msg" => $msg, "tipomsg" => $tipomsg]);

        }

}