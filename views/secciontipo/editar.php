<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\SeccionTipo;

$this->title = 'Editar Seccion Tipo';
?>

<h1>Editar registro con Id <?= Html::encode($_GET["seccion_tipo_pk"]) ?></h1>
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

<div class="row" id="secciontipo">
    <div class="col-lg-3">
        <?= $form->field($model, 'seccion_tipo_pk')->input("hidden") ?>
        <?= $form->field($model, 'tipo')->input('text') ?>
    </div>

</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Actualizar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("secciontipo/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
