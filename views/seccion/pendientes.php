<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\Modal;
use kartik\select2\Select2;

$this->title = 'Nuevo Pago Mensualidad';

?>

<h1>Nuevo Pago Mensualidad</h1>
<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("pagos/pagospendientes"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
   
	'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],    
]);
?>

<div class="panel panel-primary panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>	
    <div class="panel-body" id="nuevopago">
        <div class="row" >
            <?= $formulario->field($form, "identificacion")->input("search") ?>                        
        </div>
        <div class="panel-footer text-right">            
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary",]) ?>
            <a align="right" href="<?= Url::toRoute("pagos/pagospendientes") ?>" class="btn btn-primary"> Actualizar</a>
            <a align="right" href="<?= Url::toRoute("pagos/index") ?>" class="btn btn-primary"> Regresar</a>
        </div>
    </div>
</div>


<div class="table-responsive">
<div class="panel panel-primary ">
    <div class="panel-heading">
        Pagos Pendientes de  <?= $registro ?>
    </div>
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Consecutivo</th>                                                                                                
                <th scope="col">Mensualidad</th>                
                <th scope="col">Valor a Pagar</th>               
                <th scope="col"></th>                
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>                                
                <td><?= $val->consecutivo ?></td>
                <td><?= $val->mensualidad ?></td>
                <td><?= "$ ". number_format($val->total) ?></td>                
                <td><?= Html::a('<span class="glyphicon glyphicon-usd"></span> Pagar', ['nuevo', 'consecutivo' => $val->consecutivo]); ?></td>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>        
    </div>
</div>


<?php $formulario->end() ?>





