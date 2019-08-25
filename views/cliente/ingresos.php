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

$this->title = 'Clientes Ingresos';
?>

    <h1>Clientes Ingresos</h1>
<?php $f = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("cliente/ingresos"),
    "enableClientValidation" => true,
    'options' => ['class' => 'form-horizontal'],
    'fieldConfig' => [
                    'template' => '{label}<div class="col-sm-4 form-group">{input}{error}</div>',
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                    'options' => []
                ],

]);

$sede = ArrayHelper::map(\app\models\Sedes::find()->where(['=','estado',0])->all(), 'sede_pk','sede');
?>
<div class="panel panel-default panel-filters">
    <div class="panel-heading panel-x">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtrocliente">
        <div class="row" >            
            <?= $f->field($form,'fechacreacion')->widget(DatePicker::className(),['name' => 'check_issue_date',
                'value' => date('d-m-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]]) ?>
            <?= $f->field($form, 'anio_mes_dia')->dropDownList(['dia' => 'Dia','mes' => 'Mes','anio' => 'Año'],['prompt' => 'Seleccione...' ]) ?>
            <?= $f->field($form, 'sede_fk')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
        </div> 
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("cliente/ingresos") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div> 

<?php $f->end() ?>
        
    <div class="alert alert-info">Registros: <?= $pagination->totalCount ?></div>
    <div class="table-condensed">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Fecha Ingreso</th>
                <th scope="col">Cliente</th>                
                <th scope="col">Teléfono</th>
                <th scope="col">Celular</th>
                <th scope="col">Email</th>                
                <th scope="col">Dirección</th>                
                <th scope="col">Sede</th>                                                
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>                                                
                <td><?= $val->cliente_pk ?></td>
                <td><?= date("Y-m-d",strtotime($val->fechacreacion)) ?></td>
                <td><?= $val->nombrecompleto ?></td>                
                <td><?= $val->telefono ?></td>
                <td><?= $val->celular ?></td>
                <td><?= $val->email ?></td>
                <td><?= $val->direccion ?></td>
                <td><?= $val->sedeFk->sede ?></td>                                
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>        
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination] ); ?>                      
        </div>
    </div>

