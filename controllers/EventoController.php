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

class EventoController extends Controller {

    public function actionIndex() {
        if (!Yii::$app->user->isGuest) {
            $usuario = \app\models\Users::find()->where(['=', 'id', Yii::$app->user->identity->id])->one();
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
                    if ($anio_mes_dia == "dia") {
                        $fecha = $fecha;
                    }
                    if ($anio_mes_dia == "mes") {
                        $fecha = date('Y-m', strtotime($fecha));
                    }
                    if ($anio_mes_dia == "anio") {
                        $fecha = date('Y', strtotime($fecha));
                    }
                    if ($usuarioperfil == 2) { //administrador
                        $table = Eventos::find()
                                ->andFilterWhere(['like', 'fechai', $fecha])
                                ->andFilterWhere(['=', 'identificacion', $identificación])
                                ->andFilterWhere(['like', 'nombres', $cliente])
                                ->andFilterWhere(['like', 'maquina', $maquina])
                                ->andFilterWhere(['like', 'sede_fk', $sede])
                                ->orderBy('fechai asc');
                    } else {//administrativo
                        $table = Eventos::find()
                                ->where(['=', 'sede_fk', $usuariosede])
                                ->andFilterWhere(['like', 'fechai', $fecha])
                                ->andFilterWhere(['=', 'identificacion', $identificación])
                                ->andFilterWhere(['like', 'nombres', $cliente])
                                ->andFilterWhere(['like', 'maquina', $maquina])
                                ->andFilterWhere(['like', 'sede_fk', $sede])
                                ->orderBy('fechai asc');
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
                if (isset($_POST['excel'])) {
                    if ($usuarioperfil == 2) { //administrador
                        $table = Eventos::find()
                                ->andFilterWhere(['like', 'fechai', $fecha])
                                ->andFilterWhere(['=', 'identificacion', $identificación])
                                ->andFilterWhere(['like', 'nombres', $cliente])
                                ->andFilterWhere(['like', 'maquina', $maquina])
                                ->andFilterWhere(['like', 'sede_fk', $sede])
                                ->orderBy('fechai asc');
                    } else {//administrativo
                        $table = Eventos::find()
                                ->where(['=', 'sede_fk', $usuariosede])
                                ->andFilterWhere(['like', 'fechai', $fecha])
                                ->andFilterWhere(['=', 'identificacion', $identificación])
                                ->andFilterWhere(['like', 'nombres', $cliente])
                                ->andFilterWhere(['like', 'maquina', $maquina])
                                ->andFilterWhere(['like', 'sede_fk', $sede])
                                ->orderBy('fechai asc');
                    }
                    $model = $table
                            ->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
                    $this->actionExcel($model);
                }
            } else {
                if ($usuarioperfil == 2) { //administrador
                    $table = Eventos::find()
                            ->orderBy('fechai asc');
                } else {//administrativo
                    $table = Eventos::find()
                            ->where(['=', 'sede_fk', $usuariosede])
                            ->orderBy('fechai asc');
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
                if (isset($_POST['excel'])) {
                    //$this->actionExcel($model);                    
                }
            }
            return $this->render('index', [
                        'model' => $model,
                        'form' => $form,
                        'pagination' => $pages,
            ]);
        } else {
            return $this->redirect(["site/login"]);
        }
    }

    public function actionNuevo() {
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
                $cliente = \app\models\Cliente::find()->where(['=', 'identificacion', $model->identificacion])->one();
                $table->nombres = $cliente->nombrecompletosinidentificacion;
                $table->asunto = $model->asunto;
                $table->fechai = date("Y-m-d", strtotime($model->fechai)) . ' ' . $model->horai;
                //$fechai = $table->fechai;
                $table->fechat = date("Y-m-d", strtotime($model->fechai)) . ' ' . $model->horaf;
                /* $minutos = Maquina::find()->where(['=','id_maquina',$model->maquina])->one();
                  $minutos = '+'.$minutos->duracion.' minute';
                  $nuevafecha = strtotime ( $minutos , strtotime ( $fecha ) ) ;
                  $nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha ); */
                //$table->fechat = $nuevafecha;
                $table->id_profesional = $model->id_profesional;
                $table->telefono = $model->telefono;
                $table->maquina = $model->maquina;
                $table->sede_fk = $model->sede_fk;
                $table->observaciones = $model->observaciones;
                $consultacitarep = Eventos::find()
                        ->where(['=', 'fechai', $table->fechai])
                        ->andWhere(['=', 'sede_fk', $table->sede_fk])
                        ->andWhere(['=', 'maquina', $table->maquina])
                        ->andWhere(['=', 'cancelo_no_asistio', 0])
                        ->all();
                if (!$consultacitarep) {
                    if ($table->insert()) {
                        $msg = "Registros guardados correctamente";
                        $model->identificacion = null;
                        $model->asunto = null;
                        $model->fechai = null;
                        $model->horai = null;
                        $model->horaf = null;
                        $model->id_profesional = null;
                        $model->telefono = null;
                        $model->maquina = null;
                        $model->sede_fk = null;
                        $model->observaciones = null;
                    } else {
                        $tipomsg = "danger";
                        $msg = "Error en la insercion";
                    }
                } else {
                    $tipomsg = "danger";
                    $msg = "Ya existe una cita programada en ese horario, en la misma sede y con la misma maquina";
                }
            } else {
                $model->getErrors();
            }
        }

        return $this->render('nuevo', ['model' => $model, 'msg' => $msg, 'tipomsg' => $tipomsg]);
    }

    public function actionEditar() {
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
                    $cliente = \app\models\Cliente::find()->where(['=', 'identificacion', $model->identificacion])->one();
                    $table->nombres = $cliente->nombrecompletosinidentificacion;
                    $table->asunto = $model->asunto;
                    $table->fechai = date("Y-m-d", strtotime($model->fechai)) . ' ' . $model->horai;
                    $table->fechat = date("Y-m-d", strtotime($model->fechai)) . ' ' . $model->horaf;
                    //$fecha = $table->fechai;
                    //$minutos = Maquina::find()->where(['=','id_maquina',$model->maquina])->one();
                    //$minutos = '+'.$minutos->duracion.' minute';
                    //$nuevafecha = strtotime ( $minutos , strtotime ( $fecha ) ) ;                    
                    //$nuevafecha = date ( 'Y-m-d H:i:s' , $nuevafecha );
                    //$table->fechat = $nuevafecha;
                    $table->id_profesional = $model->id_profesional;
                    $table->telefono = $model->telefono;
                    $table->maquina = $model->maquina;
                    $table->sede_fk = $model->sede_fk;
                    $table->observaciones = $model->observaciones;
                    $consultacitarep = Eventos::find()
                            ->where(['=', 'fechai', $table->fechai])
                            ->andWhere(['=', 'sede_fk', $table->sede_fk])
                            ->andWhere(['=', 'maquina', $table->maquina])
                            ->andWhere(['=', 'cancelo_no_asistio', 0])
                            ->andWhere(['<>', 'id', $model->id])
                            ->all();
                    if (!$consultacitarep) {
                        if ($table->update()) {
                            $msg = "El registro ha sido actualizado correctamente";
                        } else {
                            $msg = "El registro no sufrio ningun cambio";
                            $tipomsg = "danger";
                        }
                    } else {
                        $tipomsg = "danger";
                        $msg = "Ya existe una cita programada en ese horario, en la misma sede y con la misma maquina";
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
                $model->fechai = date("Y-m-d", strtotime($table->fechai));
                $model->horai = date("H:i:s", strtotime($table->fechai));
                $model->horaf = date("H:i:s", strtotime($table->fechat));
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
            if ($evento->cancelo_no_asistio == 0) {
                $evento->cancelo_no_asistio = 1;
                $evento->color = "yellow";
            } else {
                $evento->cancelo_no_asistio = 0;
                $evento->color = "";
            }
            $evento->save(false);
            $this->redirect(["evento/index"]);
        } else {
            $this->redirect(["evento/index"]);
        }
    }

    public function actionIndexevento() {
        if (!Yii::$app->user->isGuest) {
            $usuario = \app\models\Users::find()->where(['=', 'id', Yii::$app->user->identity->id])->one();
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
            } else {
                if ($usuarioperfil == 2) { //administrador
                    $events = Eventos::find()->all();
                } else { //administrativo
                    $events = Eventos::find()->where(['=', 'sede_fk', $usuariosede])->all();
                }

                return $this->render('indexevento', [
                            'events' => $events,
                            'form' => $form,
                ]);
            }
        } else {
            return $this->redirect(["site/login"]);
        }
    }

    public function actionExcel($model) {
        //$costoproducciondiario = CostoProduccionDiaria::find()->all();
        $objPHPExcel = new \PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("EMPRESA")
                ->setLastModifiedBy("EMPRESA")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
        $objPHPExcel->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
        $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Id')
                ->setCellValue('B1', 'Fecha Cita')
                ->setCellValue('C1', 'Hora Inicio')
                ->setCellValue('D1', 'Hora Final')
                ->setCellValue('E1', 'Asunto')
                ->setCellValue('F1', 'Cliente')
                ->setCellValue('G1', 'Documento')
                ->setCellValue('H1', 'Sede')
                ->setCellValue('I1', 'Maquina')
                ->setCellValue('J1', 'Telefono')
                ->setCellValue('k1', 'Canceló');

        $i = 2;

        foreach ($model as $val) {
            if ($val->cancelo_no_asistio == 0) {
                $cancelo = "NO";
            } else {
                $cancelo = "SI";
            }
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A' . $i, $val->id)
                    ->setCellValue('B' . $i, date("Y-m-d", strtotime($val->fechai)))
                    ->setCellValue('C' . $i, date("H:i", strtotime($val->fechai)))
                    ->setCellValue('D' . $i, date("H:i", strtotime($val->fechat)))
                    ->setCellValue('E' . $i, $val->asunto)
                    ->setCellValue('F' . $i, $val->nombres)
                    ->setCellValue('G' . $i, $val->identificacion)
                    ->setCellValue('H' . $i, $val->sedeFk->sede)
                    ->setCellValue('I' . $i, $val->maquina0->maquina)
                    ->setCellValue('J' . $i, $val->telefono)
                    ->setCellValue('K' . $i, $val->cancelo);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('citas');
        $objPHPExcel->setActiveSheetIndex(0);

        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="citas.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->save('php://output');
        //return $model;
        exit;
    }

}
