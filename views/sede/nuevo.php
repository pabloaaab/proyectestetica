<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Crear Sede';
?>

<h1>Nuevo Sede</h1>
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

<div class="row" id="sede">
    <div class="col-lg-3">
        <?= $form->field($model, 'sede_pk')->input("hidden") ?>
        <?= $form->field($model, 'sede')->input("text") ?>
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Crear", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("sede/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
