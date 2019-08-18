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
use app\models\Historia;
use app\models\FormatoAutorizacion;
use yii\helpers\Url;
use app\models\FormFiltroHistoria;
use yii\web\UploadedFile;
use app\models\FormFirmaCliente;

    class HistoriaController extends Controller
    {

        public function actionIndex()
        {
            if (!Yii::$app->user->isGuest) {
                $usuario = \app\models\Users::find()->where(['=','id',Yii::$app->user->identity->id])->one();
                $usuarioperfil = $usuario->role;
                $usuariosede = $usuario->sede_fk;
                $form = new FormFiltroHistoria;
                $search = null;
                if ($form->load(Yii::$app->request->get())) {
                    if ($form->validate()) {
                        $search = Html::encode($form->q);
                        if ($usuarioperfil == 2) { //administrador
                            $table = Historia::find()                            
                            ->andFilterWhere(['like', 'identificacion', $search])
                            ->orderBy('id_historia desc');
                        }else{//administrativo
                            $table = Historia::find()                            
                            ->Where(['=','sede_fk',$usuariosede])        
                            ->andFilterWhere(['like', 'identificacion', $search])                            
                            ->orderBy('id_historia desc');
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
                        $table = Historia::find()                                
                        ->orderBy('id_historia desc');
                    }else{ //administrativo
                        $table = Historia::find()
                        ->where(['=','sede_fk',$usuariosede])
                        ->orderBy('id_historia desc');
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

        public function actionNuevo()
        {
            $model = new Historia();
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = new Historia;                    
                    $table->nombre = $model->nombre;
                    $table->identificacion = $model->identificacion;
                    $table->fecha_nacimiento = $model->fecha_nacimiento;
                    $table->fecha_creacion = date('Y-m-d');
                    $table->email = $model->email;
                    $table->ocupacion = $model->ocupacion;
                    $table->sexo = $model->sexo;
                    $table->edad = $model->edad;
                    $table->telefono = $model->telefono;
                    $table->celular = $model->celular;
                    $table->direccion = $model->direccion;
                    $table->estado_civil = $model->estado_civil;
                    $table->lugar_residencia = $model->lugar_residencia;
                    $table->aseguradora_salud = $model->aseguradora_salud;
                    $table->consumo_grasa = $model->consumo_grasa;
                    $table->consumo_azucar = $model->consumo_azucar;
                    $table->fuma = $model->fuma;
                    $table->cuanto_diario = $model->cuanto_diario;
                    $table->alcohol = $model->alcohol;
                    $table->consumo_alcohol = $model->consumo_alcohol;
                    $table->duerme_bien = $model->duerme_bien;
                    $table->hace_ejercicio = $model->hace_ejercicio;
                    $table->clase = $model->clase;
                    $table->frecuencia = $model->frecuencia;
                    $table->observaciones = $model->observaciones;
                    $table->motivo_consulta = $model->motivo_consulta;
                    $table->enfermedad_actual = $model->enfermedad_actual;
                    $table->revision_por_sistema = $model->revision_por_sistema;
                    $table->aph_patologia = $model->aph_patologia;
                    $table->aph_patologia_t = $model->aph_patologia_t;
                    $table->aph_quirurgicos = $model->aph_quirurgicos;
                    $table->aph_quirurgicos_t = $model->aph_quirurgicos_t;
                    $table->aph_alergicos = $model->aph_alergicos;
                    $table->aph_alergicos_t = $model->aph_alergicos_t;
                    $table->aph_toxicos = $model->aph_toxicos;
                    $table->aph_toxicos_t = $model->aph_toxicos_t;
                    $table->apm_agregar = $model->apm_agregar;
                    $table->apm_agregar_t = $model->apm_agregar_t;
                    $table->apm_menarca = $model->apm_menarca;
                    $table->apm_fecha_ultima_m = $model->apm_fecha_ultima_m;
                    $table->apm_embarazo = $model->apm_embarazo;
                    $table->apm_metodo_planificar = $model->apm_metodo_planificar;
                    $table->examen_fisico = $model->examen_fisico;
                    $table->presion_arterial = $model->presion_arterial;
                    $table->frecuencia_cardiaca = $model->frecuencia_cardiaca;
                    $table->frecuencia_respiratoria = $model->frecuencia_respiratoria;
                    $table->peso = $model->peso;
                    $table->talla = $model->talla;
                    $table->imc = $model->imc;
                    $table->cabeza = $model->cabeza;
                    $table->cuello = $model->cuello;
                    $table->cardio_respiratorio = $model->cardio_respiratorio;
                    $table->abdomen = $model->abdomen;
                    $table->extremidades = $model->extremidades;
                    $table->piel = $model->piel;
                    $table->af_observaciones = $model->af_observaciones;
                    $table->sede_fk = $model->sede_fk;
                    if ($table->insert()) {
                        $msg = "Registros guardados correctamente";
                        $model->nombre = '';
                        $model->identificacion = '';
                        $model->fecha_nacimiento = '';
                        $model->email = '';
                        $model->ocupacion = '';
                        $model->sexo = '';
                        $model->edad = '';
                        $model->telefono = '';
                        $model->celular = '';
                        $model->direccion = '';
                        $model->estado_civil = '';
                        $model->lugar_residencia = '';
                        $model->aseguradora_salud = '';
                        $model->consumo_grasa = '';
                        $model->consumo_azucar = '';
                        $model->fuma = '';
                        $model->cuanto_diario = '';
                        $model->alcohol = '';
                        $model->consumo_alcohol = '';
                        $model->duerme_bien = '';
                        $model->hace_ejercicio = '';
                        $model->clase = '';
                        $model->frecuencia = '';
                        $model->observaciones = '';
                        $model->motivo_consulta = '';
                        $model->enfermedad_actual = '';
                        $model->revision_por_sistema = '';
                        $model->aph_patologia = '';
                        $model->aph_patologia_t = '';
                        $model->aph_quirurgicos = '';
                        $model->aph_quirurgicos_t = '';
                        $model->aph_alergicos = '';
                        $model->aph_alergicos_t = '';
                        $model->aph_toxicos = '';
                        $model->aph_toxicos_t = '';
                        $model->apm_agregar = '';
                        $model->apm_agregar_t = '';
                        $model->apm_menarca = '';
                        $model->apm_fecha_ultima_m = '';
                        $model->apm_embarazo = '';
                        $model->apm_metodo_planificar = '';
                        $model->examen_fisico = '';
                        $model->presion_arterial = '';
                        $model->frecuencia_cardiaca = '';
                        $model->frecuencia_respiratoria = '';
                        $model->peso = '';
                        $model->talla = '';
                        $model->imc = '';
                        $model->cabeza = '';
                        $model->cuello = '';
                        $model->cardio_respiratorio = '';
                        $model->abdomen = '';
                        $model->extremidades = '';
                        $model->piel = '';
                        $model->af_observaciones = '';
                        $model->sede_fk = '';                                                                                                                        
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
            $model = new Historia;
            $msg = null;
            $tipomsg = null;
            if ($model->load(Yii::$app->request->post()) && Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            if ($model->load(Yii::$app->request->post())) {
                if ($model->validate()) {
                    $table = Historia::find()->where(['id_historia' => $model->id_historia])->one();                    
                    if ($table) {
                        $table->nombre = $model->nombre;
                        $table->identificacion = $model->identificacion;
                        $table->fecha_nacimiento = $model->fecha_nacimiento;
                        $table->fecha_modificacion = date('Y-m-d');
                        $table->email = $model->email;
                        $table->ocupacion = $model->ocupacion;
                        $table->sexo = $model->sexo;
                        $table->edad = $model->edad;
                        $table->telefono = $model->telefono;
                        $table->celular = $model->celular;
                        $table->direccion = $model->direccion;
                        $table->estado_civil = $model->estado_civil;
                        $table->lugar_residencia = $model->lugar_residencia;
                        $table->aseguradora_salud = $model->aseguradora_salud;
                        $table->consumo_grasa = $model->consumo_grasa;
                        $table->consumo_azucar = $model->consumo_azucar;
                        $table->fuma = $model->fuma;
                        $table->cuanto_diario = $model->cuanto_diario;
                        $table->alcohol = $model->alcohol;
                        $table->consumo_alcohol = $model->consumo_alcohol;
                        $table->duerme_bien = $model->duerme_bien;
                        $table->hace_ejercicio = $model->hace_ejercicio;
                        $table->clase = $model->clase;
                        $table->frecuencia = $model->frecuencia;
                        $table->observaciones = $model->observaciones;
                        $table->motivo_consulta = $model->motivo_consulta;
                        $table->enfermedad_actual = $model->enfermedad_actual;
                        $table->revision_por_sistema = $model->revision_por_sistema;
                        $table->aph_patologia = $model->aph_patologia;
                        $table->aph_patologia_t = $model->aph_patologia_t;
                        $table->aph_quirurgicos = $model->aph_quirurgicos;
                        $table->aph_quirurgicos_t = $model->aph_quirurgicos_t;
                        $table->aph_alergicos = $model->aph_alergicos;
                        $table->aph_alergicos_t = $model->aph_alergicos_t;
                        $table->aph_toxicos = $model->aph_toxicos;
                        $table->aph_toxicos_t = $model->aph_toxicos_t;
                        $table->apm_agregar = $model->apm_agregar;
                        $table->apm_agregar_t = $model->apm_agregar_t;
                        $table->apm_menarca = $model->apm_menarca;
                        $table->apm_fecha_ultima_m = $model->apm_fecha_ultima_m;
                        $table->apm_embarazo = $model->apm_embarazo;
                        $table->apm_metodo_planificar = $model->apm_metodo_planificar;
                        $table->examen_fisico = $model->examen_fisico;
                        $table->presion_arterial = $model->presion_arterial;
                        $table->frecuencia_cardiaca = $model->frecuencia_cardiaca;
                        $table->frecuencia_respiratoria = $model->frecuencia_respiratoria;
                        $table->peso = $model->peso;
                        $table->talla = $model->talla;
                        $table->imc = $model->imc;
                        $table->cabeza = $model->cabeza;
                        $table->cuello = $model->cuello;
                        $table->cardio_respiratorio = $model->cardio_respiratorio;
                        $table->abdomen = $model->abdomen;
                        $table->extremidades = $model->extremidades;
                        $table->piel = $model->piel;
                        $table->af_observaciones = $model->af_observaciones;
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


            if (Yii::$app->request->get("id")) {
                $id_historia = Html::encode($_GET["id"]);
                $table = Historia::find()->where(['id_historia' => $id_historia])->one();                
                if ($table) {
                        $model->id_historia = $table->id_historia;
			$model->nombre = $table->nombre;
                        $model->identificacion = $table->identificacion;
                        $model->fecha_nacimiento = $table->fecha_nacimiento;
                        $model->email = $table->email;
                        $model->ocupacion = $table->ocupacion;
                        $model->sexo = $table->sexo;
                        $model->edad = $table->edad;
                        $model->telefono = $table->telefono;
                        $model->celular = $table->celular;
                        $model->direccion = $table->direccion;
                        $model->estado_civil = $table->estado_civil;
                        $model->lugar_residencia = $table->lugar_residencia;
                        $model->aseguradora_salud = $table->aseguradora_salud;
                        $model->consumo_grasa = $table->consumo_grasa;
                        $model->consumo_azucar = $table->consumo_azucar;
                        $model->fuma = $table->fuma;
                        $model->cuanto_diario = $table->cuanto_diario;
                        $model->alcohol = $table->alcohol;
                        $model->consumo_alcohol = $table->consumo_alcohol;
                        $model->duerme_bien = $table->duerme_bien;
                        $model->hace_ejercicio = $table->hace_ejercicio;
                        $model->clase = $table->clase;
                        $model->frecuencia = $table->frecuencia;
                        $model->observaciones = $table->observaciones;
                        $model->motivo_consulta = $table->motivo_consulta;
                        $model->enfermedad_actual = $table->enfermedad_actual;
                        $model->revision_por_sistema = $table->revision_por_sistema;
                        $model->aph_patologia = $table->aph_patologia;
                        $model->aph_patologia_t = $table->aph_patologia_t;
                        $model->aph_quirurgicos = $table->aph_quirurgicos;
                        $model->aph_quirurgicos_t = $table->aph_quirurgicos_t;
                        $model->aph_alergicos = $table->aph_alergicos;
                        $model->aph_alergicos_t = $table->aph_alergicos_t;
                        $model->aph_toxicos = $table->aph_toxicos;
                        $model->aph_toxicos_t = $table->aph_toxicos_t;
                        $model->apm_agregar = $table->apm_agregar;
                        $model->apm_agregar_t = $table->apm_agregar_t;
                        $model->apm_menarca = $table->apm_menarca;
                        $model->apm_fecha_ultima_m = $table->apm_fecha_ultima_m;
                        $model->apm_embarazo = $table->apm_embarazo;
                        $model->apm_metodo_planificar = $table->apm_metodo_planificar;
                        $model->examen_fisico = $table->examen_fisico;
                        $model->presion_arterial = $table->presion_arterial;
                        $model->frecuencia_cardiaca = $table->frecuencia_cardiaca;
                        $model->frecuencia_respiratoria = $table->frecuencia_respiratoria;
                        $model->peso = $table->peso;
                        $model->talla = $table->talla;
                        $model->imc = $table->imc;
                        $model->cabeza = $table->cabeza;
                        $model->cuello = $table->cuello;
                        $model->cardio_respiratorio = $table->cardio_respiratorio;
                        $model->abdomen = $table->abdomen;
                        $model->extremidades = $table->extremidades;
                        $model->piel = $table->piel;
                        $model->af_observaciones = $table->af_observaciones;
                        $model->sede_fk = $table->sede_fk;                    
                } else {
                    return $this->redirect(["historia/index"]);
                }
            } else {
                return $this->redirect(["historia/index"]);
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
                    $table = Historia::find()->where(['id' => $id])->one();                    
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
            $model = Historia::find()->where(['id_historia' => $id])->one();
            return $this->render("generarimprimir", ["model" => $model]);
        }

}