<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Pago Seccion';
?>

<h1>Pagos Sección</h1>
<?php
$f = ActiveForm::begin([
            "method" => "get",
            "action" => Url::toRoute("seccion/index"),
            "enableClientValidation" => true,
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                            'template' => '{label}<div class="col-sm-10 form-group">{input}{error}</div>',
                            'labelOptions' => ['class' => 'col-sm-2 control-label'],
                            'options' => []
                        ],
]);
?>
<div class="panel panel-default panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtromatriculas">
        <div class="row" >
            <?= $f->field($form, "identificacion")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("seccion/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>
<?php $f->end() ?>

<div class = "form-group" align="right">
    <a align="right" href="<?= Url::toRoute("seccion/nuevo") ?>" class="btn btn-primary">Nuevo Seccion Pago</a>
</div>
<div class="alert alert-info">Registros: <?= $pagination->totalCount ?></div>

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
                <th scope="col"></th>
                <th scope="col"></th>
                <th scope="col"></th>                                          
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
                    <td><a href="<?= Url::toRoute(["seccion/imprimirfactura", "seccion_pk" => $val->seccion_pk]) ?>" target="_blank"><img src="svg/si-glyph-print.svg" align="center" width="20px" height="20px" title="Imprimir Factura"></a></td>
                    <td><a href="<?= Url::toRoute(["seccion/imprimirfacturatirilla", "seccion_pk" => $val->seccion_pk]) ?>" target="_blank"><img src="svg/si-glyph-print.svg" align="center" width="20px" height="20px" title="Imprimir Tirilla"></a></td>                    
                    <?php if ($anulado == "SI") { ?>
                        <td></td>
                    <?php } else { ?>
                        <td><a href="<?= Url::toRoute(["seccion/anulacion", "seccion_pk" => $val->seccion_pk]) ?>"><img src="svg/si-glyph-delete.svg" align="center" width="20px" height="20px" title="Anular"></a></td>
                    <?php } ?>    
                </tr>
            </tbody>
<?php endforeach; ?>
    </table>

    <div class = "form-group" align="right">
        <a align="right" href="<?= Url::toRoute("seccion/nuevo") ?>" class="btn btn-primary">Nuevo Seccion Pago</a>
    </div>
    <div class = "form-group" align="left">
<?= LinkPager::widget(['pagination' => $pagination]) ?>
    </div>
</div>

