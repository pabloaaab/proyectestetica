<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\bootstrap\Modal;
use yii\data\Pagination;
use kartik\depdrop\DepDrop;

$this->title = 'Editar Cita';
?>

<h1>Editar Cita</h1>
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
$maquina = ArrayHelper::map(\app\models\Maquina::find()->where(['<>','id_maquina',0])->all(), 'id_maquina','maquina');
$profesional = ArrayHelper::map(\app\models\Profesionales::find()->where(['=','estado',0])->all(), 'id_profesional','nombre');
$cliente = ArrayHelper::map(\app\models\Cliente::find()->all(), 'identificacion','nombrecompleto');
?>

<div class="row" id="personal">
    <div class="col-lg-4">
        <?= $form->field($model, 'id')->input("hidden") ?>
        <?= $form->field($model, 'asunto')->input("text") ?>
        <?= $form->field($model, 'identificacion')->widget(Select2::classname(), [
            'data' => $cliente,
            'options' => ['placeholder' => 'Seleccione un paciente'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]); ?>               
        <?= $form->field($model, 'sede_fk')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'maquina')->dropDownList($maquina,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model,'fechai')->widget(DatePicker::className(),['name' => 'check_issue_date',
                'value' => date('d-m-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]]) ?>
        <?= $form->field($model, 'horai')->input("time") ?>
        <?= $form->field($model, 'horaf')->input("time") ?>
        <?= $form->field($model, 'id_profesional')->dropDownList($profesional,['prompt' => 'Seleccione...' ]) ?>
        <?= $form->field($model, 'telefono')->input("text") ?>
        <?= $form->field($model, 'observaciones')->input("text") ?>                        
        
    </div>    
</div>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Guardar", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("evento/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php $form->end() ?>
