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
use app\models\Cliente;
use app\models\Sedes;

$this->title = 'Clientes Inasistentes';
?>

<h1>Clientes Inasistentes</h1>
<?php if ($tipomsg == "danger") { ?>
    <h3 class="alert-danger"><?= $msg ?></h3>
<?php } else{ ?>
    <h3 class="alert-success"><?= $msg ?></h3>
<?php } ?>
<?php $f = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("cliente/inasistentes"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);
?>
<div class="panel panel-default panel-filters">
    <div class="panel-heading panel-x">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtrocliente">
        <div class="row" >            
            <?= $f->field($form,'fechaMesAnterior')->widget(DatePicker::className(),['name' => 'check_issue_date',
                'value' => date('d-m-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]]) ?>
            <?= $f->field($form,'fechaMesActual')->widget(DatePicker::className(),['name' => 'check_issue_date',
                'value' => date('d-m-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]]) ?>
        </div> 
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("cliente/inasistentes") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div> 

<?php $f->end() ?>
        
    <div class="alert alert-info">Registros: <?= $registros ?></div>
    <div class="table-condensed">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Identificación</th>
                <th scope="col">Cliente</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Celular</th>
                <th scope="col">Email</th>
                <th scope="col">Sede</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): 
                $cliente = Cliente::findOne($val[0]); ?>                
            <tr>                                                
                <td><?= $cliente->cliente_pk ?></td>
                <td><?= $cliente->identificacion ?></td>
                <td><?= $cliente->nombrecompletosinidentificacion ?></td>
                <td><?= $cliente->telefono ?></td>
                <td><?= $cliente->celular ?></td>
                <td><?= $cliente->email ?></td>
                <td><?= $cliente->sedeFk->sede ?></td>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>                
    </div>

