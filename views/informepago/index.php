<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use moonland\phpexcel\Excel;

$this->title = 'Informe Pagos';
?>

<h1>Informe Pagos</h1>
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("informepago/index"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
?>

<?php
$sede = ArrayHelper::map(\app\models\Sedes::find()->where(['=','estado',0])->all(), 'sede_pk','sede');
?>    
    
<div class="panel panel-default panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtromatriculas">
        <div class="row" >
            <?= $formulario->field($form, "identificacion")->input("search") ?>                       
            <?= $formulario->field($form, 'sede_fk')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
            <?= $formulario->field($form,'fecha_pago')->widget(DatePicker::className(),['name' => 'check_issue_date',
                'value' => date('d-m-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]]) ?>                                         
            <?= $formulario->field($form, "seccion_pk")->input("search") ?>
        </div>
        <div class="row">
            <?= $formulario->field($form, 'anio_mes_dia')->radio(['label' => 'Fecha Dia','value' => "dia", 'uncheck' => null]) ?>
            <?= $formulario->field($form, 'anio_mes_dia')->radio(['label' => 'Fecha Mes','value' => "mes", 'uncheck' => null]) ?>
            <?= $formulario->field($form, 'anio_mes_dia')->radio(['label' => 'Fecha Anio','value' => "anio", 'uncheck' => null]) ?>
            </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("informepago/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>    
    


<!-- Trigger the modal with a button -->

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Informe</h4>
      </div>
      <div class="modal-body">                                 
        <p class="alert-info">Total pagos sede Medellin: <?= '$ '.number_format($result[0]['pagosmedellin']) ?></p>
        <p class="alert-danger">Total pagos sede Medellin Anulados: <?= '$ '.number_format($result[0]['pagosmedellinanulado']) ?></p>
        <p class="alert-info">Total pagos sede Envigado: <?= '$ '.number_format($result[0]['pagosenvigado']) ?></p>
        <p class="alert-danger">Total pagos sede Envigado Anulados: <?= '$ '.number_format($result[0]['pagosenvigadoanulado']) ?></p>
        <p class="alert-info">Subtotal: <?= '$ '.number_format($subtotal) ?></p>
        <p class="alert-danger">Total Anulados: <?= '$ '.number_format($totalanulado) ?></p>        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>

<div class="alert alert-info">Registros: <?= $pagination->totalCount ?> <a class="btn btn-default" data-toggle="modal" data-target="#myModal">Ver informe</a></div>
<div class="table-condensed">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th scope="col">N° Pago</th>                                
                <th scope="col">Cliente</th>                                                
                <th scope="col">Fecha</th>
                <th scope="col">Seccion</th>                
                <th scope="col">Valor Seccion</th>
                <th scope="col">Fecha Pago</th>
                <th scope="col">Valor Pago</th>
                <th scope="col">Sede</th>
                <th scope="col">Pagado</th>
                <th scope="col">Anulado</th>
                <th scope="col">Observaciones</th> 
                <th scope="col">Cc</th> 
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model as $val): ?>
                <tr>
                    <?php if ($val->estado_anulado == "") {
                        $anulado = "NO";
                    } else {
                        $anulado = "SI";
                    } ?>
                    <?php if ($val->estado_pagado == "SI") {
                        $pagado = "SI";
                    } else {
                        $pagado = "NO";
                    } ?>
                    <?php if ($val->centro_costo == 0) {
                        $cc = "NO";
                    } else {
                        $cc = "SI";
                    } ?>
                    <th scope="row"><?= $val->seccion_pk ?></th>                
                    <td><?= $val->clienteFk->nombrecompleto ?></td>
                    <td><?= $val->fecha ?></td>
                    <td><?= $val->seccionTipoFk->tipo ?></td>                                                                    
                    <td><?= number_format($val->valor_seccion) ?></td>
                    <td><?= $val->fecha_pago ?></td>
                    <td><?= number_format($val->total_pago) ?></td>
                    <td><?= $val->sedeFk->sede ?></td>                    
                    <td align="center"><?= $pagado ?></td>                 
                    <td align="center"><?= $anulado ?></td>
                    <td><?= $val->observaciones ?></td>
                    <td><?= $cc ?></td>
                    <td><a href="<?= Url::toRoute(["informepago/cc", "id" => $val->seccion_pk]) ?>" ><img src="svg/si-glyph-document-checked.svg" align="center" width="20px" height="20px" title="cc"></a></td>                   
                </tr>
            </tbody>
<?php endforeach; ?>
    </table>    
    
    <div class = "form-group" align="left">
        <?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>    
</div>

<?php $formulario->end() ?>

<?php
$form = ActiveForm::begin([
            "method" => "post",
            'id' => 'formulario',
            'enableClientValidation' => false,
            'enableAjaxValidation' => true,
            'options' => ['class' => 'form-horizontal condensed', 'role' => 'form'],
            'fieldConfig' => [
                'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                'labelOptions' => ['class' => 'col-sm-2 control-label'],
                'options' => []
            ],
        ]);
?>
<div class="panel-footer text-right">
    <?= Html::submitButton("<span class='glyphicon glyphicon-export'></span> excel", ["class" => "btn btn-primary", 'name' => 'excel', 'value' => 1]) ?>        
</div>

<?php $formulario->end() ?>