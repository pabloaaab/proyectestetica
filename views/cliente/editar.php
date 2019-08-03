<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Municipio;
use app\models\Departamentos;

$this->title = 'Editar Inscrito';
?>

<h1>Editar registro con Consecutivo <?= Html::encode($_GET["consecutivo"]) ?></h1>
<?php if ($tipomsg == "danger") { ?>
    <h3 class="alert-danger"><?= $msg ?></h3>
<?php } else{ ?>
    <h3 class="alert-success"><?= $msg ?></h3>
<?php } ?>

<?php $form = ActiveForm::begin([
    "method" => "post",
    'id' => 'formulario',
    'enableClientValidation' => false,
    'enableAjaxValidation' => true,
]);
?>

<?php
$lugarExp = ArrayHelper::map(Municipio::find()->all(), 'opcion','opcion');
$municipioNac = ArrayHelper::map(Municipio::find()->all(), 'opcion','opcion');
//$sede = ArrayHelper::map(Municipio::find()->all(), 'opcion','opcion');
$sede = ArrayHelper::map(\app\models\Sede::find()->where(['=','estado',1])->all(), 'sede','sede');
$departamento_nac = ArrayHelper::map(Departamentos::find()->all(), 'opcion','opcion');
$ciudadFirma = ArrayHelper::map(Municipio::find()->all(), 'opcion','opcion');
?>

<h3>Información Personal</h3>
<div class="row" id="personal">
    <div class="col-lg-3">
        <?= $form->field($model, 'consecutivo')->input("hidden") ?>
        <?= $form->field($model, 'identificacion')->input("text") ?>
        <?= $form->field($model, 'nombre1')->input("text") ?>
        <?= $form->field($model, 'apellido1')->input("text") ?>
        <?= $form->field($model, 'tipo_personal')->dropdownList(['Estudiante' => 'Estudiante', 'Administrativo' => 'Administrativo', 'Docente' => 'Docente'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'telefono')->input("text") ?>
        <?= $form->field($model, 'email')->input("text") ?>
        <?= $form->field($model, 'sexo')->dropdownList(['Masculino' => 'Masculino', 'Femenino' => 'Femenino'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'barrio')->input("text") ?>
        <?= $form->field($model, 'municipio_nac')->dropDownList($municipioNac,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'municipio')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
    </div>
    <div class="col-lg-3">
        <?= $form->field($model, 'a')->input("hidden") ?>
        <?= $form->field($model, 'tipo_doc')->dropdownList(['cc' => 'Cédula de Ciudadanía', 'ti' => 'Tarjeta de Identidad', 'ce' => 'Cedula de Extranjería'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'nombre2')->input("text") ?>
        <?= $form->field($model, 'apellido2')->input("text") ?>
        <?= $form->field($model, 'lugar_exp')->dropDownList($lugarExp,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'celular')->input("text") ?>
        <?= $form->field($model, 'direccion')->input("text") ?>
        <?= $form->field($model, 'comuna')->input("text") ?>
        <?= $form->field($model, 'fecha_nac')->input("date") ?>
        <?= $form->field($model, 'departamento_nac')->dropDownList($departamento_nac,['prompt' => 'Seleccione...' ]) ?>
    </div>
</div>
<h3>Información Familiar</h3>
<div class="row" id="familiar">
    <div class="col-lg-3">
        <?= $form->field($model, 'nom_madre')->input("text") ?>
        <?= $form->field($model, 'nom_padre')->input("text") ?>
    </div>
    <div class="col-lg-2">
        <?= $form->field($model, 'doc_madre')->input("text") ?>
        <?= $form->field($model, 'doc_padre')->input("text") ?>
    </div>
    <div class="col-lg-3">
        <?= $form->field($model, 'ocupacion_madre')->input("text") ?>
        <?= $form->field($model, 'ocupacion_padre')->input("text") ?>
    </div>
</div>
<h3>Últimos Estudios Realizados</h3>
<div class="row" id="estudios">
    <div class="col-lg-3">
        <?= $form->field($model, 'estudio1')->input("text") ?>
        <?= $form->field($model, 'estudio2')->input("text") ?>
    </div>
    <div class="col-lg-2">
        <?= $form->field($model, 'gradoc1')->input("text") ?>
        <?= $form->field($model, 'gradoc2')->input("text") ?>
    </div>
    <div class="col-lg-2">
        <?= $form->field($model, 'anioc1')->input("date") ?>
        <?= $form->field($model, 'anioc2')->input("date") ?>
    </div>
    <div class="col-lg-2">
        <?= $form->field($model, 'graduado1')->dropdownList(['Si' => 'Si', 'No' => 'No'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'graduado2')->dropdownList(['Si' => 'Si', 'No' => 'No'], ['prompt' => 'Seleccione...']) ?>
    </div>
</div>
<h3>Autorización de Registro</h3>
<div class="row" id="estudios">
    <div class="col-lg-2">
        <?= $form->field($model, 'autoriza')->dropdownList(['1' => 'Si', '0' => 'No'], ['prompt' => 'Seleccione...']) ?>
    </div>
    <div class="col-lg-2">
        <?= $form->field($model, 'fecha_autoriza')->input("date") ?>
    </div>
    <div class="col-lg-3">
        <?= $form->field($model, 'ciudad_firma')->dropDownList($ciudadFirma,['prompt' => 'Seleccione...' ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Actualizar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("inscritos/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
