<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Sedes;

$this->title = 'Nuevo Registro';
?>

<h1>Nuevo Registro</h1>
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
$sede = ArrayHelper::map(Sedes::find()->where(['=','estado',0])->all(), 'sede_pk','sede');

?>
<div class="alert alert-info">Información Personal</div>
<div class="row" id="personal">
    <div class="col-lg-3">
        <?= $form->field($model, 'id_historia')->input("hidden") ?>
        <?= $form->field($model, 'identificacion')->input("text") ?>                        
        <?= $form->field($model, 'nombre')->input("text") ?>
        <?= $form->field($model, 'fecha_nacimiento')->input("date") ?>
        <?= $form->field($model, 'telefono')->input("text") ?>
        <?= $form->field($model, 'celular')->input("text") ?>
        <?= $form->field($model, 'direccion')->input("text") ?>
        <?= $form->field($model, 'lugar_residencia')->input("text") ?>
        <?= $form->field($model, 'ocupacion')->input("text") ?>
        <?= $form->field($model, 'estado_civil')->dropdownList(['CASADO(A)' => 'CASADO(A)', 'SOLTERO(A)' => 'SOLTERO(A)','UNION LIBRE' => 'UNION LIBRE', 'DIVORCIADO(A)' => 'DIVORCIADO(A)'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'estado_civil')->dropdownList(['MASCULINO' => 'MASCULINO', 'FEMENINO' => 'FEMENINO'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'edad')->input("text") ?>
        <?= $form->field($model, 'email')->input("email") ?>
        <?= $form->field($model, 'aseguradora_salud')->input("text") ?>
        <?= $form->field($model, 'sede_fk')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
    </div>    
</div>
<div class="alert alert-info">Hábitos</div>
<div class="row" id="personal">
    <div class="col-lg-3">
        <?= $form->field($model, 'consumo_grasa')->dropdownList(['0' => 'Sin Definir', '1' => 'Alto','2' => 'Normal', '3' => 'Bajo'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'consumo_azucar')->dropdownList(['0' => 'Sin Definir', '1' => 'Alto','2' => 'Normal', '3' => 'Bajo'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'fuma')->dropdownList(['2' => 'Sin Definir', '1' => 'SI','0' => 'NO'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'cuanto_diario')->input("text") ?>
        <?= $form->field($model, 'alcohol')->dropdownList(['2' => 'Sin Definir', '1' => 'SI','0' => 'NO'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'consumo_alcohol')->dropdownList(['0' => 'Sin Definir', '1' => 'Diario','2' => 'Semanal','2' => 'Ocasional'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'duerme_bien')->dropdownList(['2' => 'Sin Definir', '1' => 'SI','0' => 'NO'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'hace_ejercicio')->dropdownList(['2' => 'Sin Definir', '1' => 'SI','0' => 'NO'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'clase')->input("text") ?>
        <?= $form->field($model, 'frecuencia')->input("text") ?>        
    </div>    
</div>
<div class="alert alert-info">Otros</div>
<div class="row" id="personal">
    <div class="col-lg-5">
        <?= $form->field($model, 'motivo_consulta')->input("text") ?>
        <?= $form->field($model, 'enfermedad_actual')->input("text") ?>
        <?= $form->field($model, 'revision_por_sistema')->input("text") ?>
    </div>    
</div>
<div class="alert alert-info">Antecedentes Personales</div>
<div class="row" id="personal">
    <div class="col-lg-3">        
        <?= $form->field($model, 'identificacion')->input("text") ?>        
        <?= $form->field($model, 'sede_fk')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>        
    </div>    
</div>
<div class="alert alert-info">Antecedentes Familiares</div>
<div class="row" id="personal">
    <div class="col-lg-3">
       
    </div>    
</div>
<div class="alert alert-info">Observaciones Generales</div>
<div class="row" id="personal">
    <div class="col-lg-3">
        
    </div>    
</div>
<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Guardar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("historia/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
