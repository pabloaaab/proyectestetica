<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Sedes;

$this->title = 'Editar Registro';
?>

<h1>Editar Registro</h1>
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
        <?= $form->field($model, 'id')->input("hidden") ?>
        <?= $form->field($model, 'identificacion')->input("text") ?>        
        <?= $form->field($model, 'sede_fk')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'autorizacion')->dropdownList(['1' => 'Si', '0' => 'No'], ['prompt' => 'Seleccione...']) ?>
        <?= $form->field($model, 'fechaautorizacion')->input("date") ?>
    </div>    
</div>
<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Guardar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("habeasdata/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
