<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Nuevo Cliente';
?>

<h1>Nuevo Cliente</h1>
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
$sede = ArrayHelper::map(\app\models\Sedes::find()->where(['=','estado',0])->all(), 'sede_pk','sede');
?>

<h3>Información Personal</h3>
<div class="row" id="personal">
    <div class="col-lg-3">
        <?= $form->field($model, 'cliente_pk')->input("hidden") ?>
        <?= $form->field($model, 'identificacion')->input("text") ?>
        <?= $form->field($model, 'nombre1')->input("text") ?>
        <?= $form->field($model, 'nombre2')->input("text") ?>
        <?= $form->field($model, 'apellido1')->input("text") ?>        
        <?= $form->field($model, 'apellido2')->input("text") ?>
        <?= $form->field($model, 'telefono')->input("text") ?>
        <?= $form->field($model, 'email')->input("text") ?>
        <?= $form->field($model, 'celular')->input("text") ?>
        <?= $form->field($model, 'direccion')->input("text") ?>                
        <?= $form->field($model, 'sede_fk')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
    </div>    
</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Guardar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("cliente/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
