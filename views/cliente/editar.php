<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

$this->title = 'Editar Cliente';
?>

<h1>Editar registro con id <?= Html::encode($_GET["cliente_pk"]) ?></h1>
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
$usuario = \app\models\Users::find()->where(['=','id',Yii::$app->user->identity->id])->one();
$usuarioperfil = $usuario->role;
$usuariosede = $usuario->sede_fk;
if ($usuarioperfil == 2) { //administrador
    $sede = ArrayHelper::map(\app\models\Sedes::find()->where(['=','estado',0])->all(), 'sede_pk','sede');
}else{ //administrativo    
    $sede = ArrayHelper::map(\app\models\Sedes::find()->where(['=','estado',0])->andWhere(['=','sede_pk',$usuariosede])->all(), 'sede_pk','sede');
}
?>

<div class="row" id="personal">
    <div class="col-lg-3">
        <?= $form->field($model, 'cliente_pk')->input("hidden") ?>
        <?= $form->field($model, 'identificacion')->input("text") ?>
        <?= $form->field($model, 'nombre1')->input("text") ?>
        <?= $form->field($model, 'nombre2')->input("text") ?>
        <?= $form->field($model, 'apellido1')->input("text") ?>
        <?= $form->field($model, 'apellido2')->input("text") ?>                       
        <?= $form->field($model, 'telefono')->input("text") ?>
        <?= $form->field($model, 'celular')->input("text") ?>
        <?= $form->field($model, 'email')->input("text") ?>       
        <?= $form->field($model, 'direccion')->input("text") ?>
        <?= $form->field($model, 'sede_fk')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>                
    </div>    
</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Actualizar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("cliente/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
