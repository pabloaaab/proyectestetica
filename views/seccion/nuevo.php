<?php



use yii\helpers\Html;

use yii\widgets\ActiveForm;

use yii\helpers\Url;

use yii\helpers\ArrayHelper;

use app\models\Sede;

use kartik\date\DatePicker;

use kartik\select2\Select2;

use yii\bootstrap\Modal;

use yii\data\Pagination;

use kartik\depdrop\DepDrop;



$this->title = 'Nuevo Seccion Pago';

?>



<h1>Nuevo Sección Pago</h1>

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

$seccion = ArrayHelper::map(\app\models\SeccionTipo::find()->all(), 'seccion_tipo_pk','tipo');

$cliente = ArrayHelper::map(\app\models\Cliente::find()->all(), 'cliente_pk','nombrecompleto');

?>

    

<div class="row" id="seccion">

    <div class="col-lg-3">        

        <?= $form->field($model, 'cliente_fk')->widget(Select2::classname(), [

            'data' => $cliente,

            'options' => ['placeholder' => 'Seleccione un paciente'],

            'pluginOptions' => [

                'allowClear' => true

            ],

        ]); ?>

        <?= $form->field($model, 'sede_fk')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>

        <?= $form->field($model, 'seccion_tipo_fk')->dropDownList($seccion,['prompt' => 'Seleccione...' ]) ?>
        
        <?php if ($usuarioperfil == 2) { //administrador ?>

            <?= $form->field($model,'fecha_seccion')->widget(DatePicker::className(),['name' => 'check_issue_date',

                    'value' => date('d-m-Y', strtotime('+2 days')),

                    'options' => ['placeholder' => 'Seleccione una fecha ...'],

                    'pluginOptions' => [

                        'format' => 'yyyy-mm-dd',

                        'todayHighlight' => true]]) ?>
        <?php } else { ?>
        
            <?= $form->field($model, 'fecha_seccion',['inputOptions' => ['value' => date('Y-m-d', strtotime('-5 hours')), 'readonly' => true]])->input("text") ?>
        
        <?php }  ?>
        
        <?= $form->field($model, 'hora_seccion')->input("time") ?>

        <?= $form->field($model, 'valor_seccion')->input("text") ?>

        <?= $form->field($model, 'observaciones')->textArea(['maxlength' => true]) ?>

    </div>



</div>



<div class="row">

    <div class="col-lg-4">

        <?= Html::submitButton("Guardar", ["class" => "btn btn-primary"])?>

        <a href="<?= Url::toRoute("seccion/index") ?>" class="btn btn-primary">Regresar</a>

    </div>

</div>



<?php $form->end() ?>

