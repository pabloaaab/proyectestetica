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

$this->title = 'Eventos';
?>

    <h1>Eventos</h1>
<?php $f = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("evento/index"),
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
$usuario = \app\models\Users::find()->where(['=','id',Yii::$app->user->identity->id])->one();
$usuarioperfil = $usuario->role;
$usuariosede = $usuario->sede_fk;
if ($usuarioperfil == 2) { //administrador
    $sede = ArrayHelper::map(\app\models\Sedes::find()->where(['=','estado',0])->all(), 'sede_pk','sede');
}else{ //administrativo    
    $sede = ArrayHelper::map(\app\models\Sedes::find()->where(['=','estado',0])->andWhere(['=','sede_pk',$usuariosede])->all(), 'sede_pk','sede');
}

$maquina = ArrayHelper::map(\app\models\Maquina::find()->all(), 'id_maquina','maquina');
?> 
    
<div class="panel panel-default panel-filters">
    <div class="panel-heading panel-x">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtroevento">
        <div class="row" >
            <?= $f->field($form, "identificacion")->input("search") ?>                        
            <?= $f->field($form, "cliente")->input("search") ?>
            <?= $f->field($form, 'maquina')->dropDownList($maquina,['prompt' => 'Seleccione...' ]) ?>
            <?= $f->field($form, 'sede_fk')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
            <?= $f->field($form,'fecha')->widget(DatePicker::className(),['name' => 'check_issue_date',
                'value' => date('d-m-Y', strtotime('+2 days')),
                'options' => ['placeholder' => 'Seleccione una fecha ...'],
                'pluginOptions' => [
                    'format' => 'yyyy-mm-dd',
                    'todayHighlight' => true]]) ?>
            <?= $f->field($form, 'anio_mes_dia')->dropDownList(['dia' => 'Dia','mes' => 'Mes','anio' => 'Año'],['prompt' => 'Seleccione...' ]) ?>                        
        </div>       
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("evento/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div> 

<?php $f->end() ?>

    <div class = "form-group" align="right">
        <a align="right" href="<?= Url::toRoute("evento/nuevo") ?>" class="btn btn-primary">Nuevo Evento</a>
    </div>    
    <div class="alert alert-info">Registros: <?= $pagination->totalCount ?></div>
    <div class="table-condensed">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Id</th>                                
                <th scope="col">Fecha</th>
                <th scope="col">Hora I</th>
                <th scope="col">Hora F</th>
                <th scope="col">Asunto</th>
                <th scope="col">Cliente</th>
                <th scope="col">Documento</th>
                <th scope="col">Sede</th>                                              
                <th scope="col">Maquina</th>
                <th scope="col">Telefono</th>                
                <th scope="col">Cancelo?</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>                                                
                <td><?= $val->id ?></td>
                <td><?= date("Y-m-d",strtotime($val->fechai)) ?></td>
                <td><?= date("H:i",strtotime($val->fechai)) ?></td>
                <td><?= date("H:i",strtotime($val->fechat)) ?></td>
                <td><?= $val->asunto ?></td>
                <td><?= $val->nombres ?></td>
                <td><?= $val->identificacion ?></td>
                <td><?= $val->sedeFk->sede ?></td>                
                <td><?= $val->maquina0->maquina ?></td>
                <td><?= $val->telefono ?></td>                
                <td><?= $val->cancelo ?></td>                                
                <td><a href="<?= Url::toRoute(["evento/editar", "id" => $val->id]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>                   
                <td><a href="<?= Url::toRoute(["evento/cancelar", "id" => $val->id]) ?>" ><img src="svg/si-glyph-button-error.svg" align="center" width="20px" height="20px" title="Cancelar"></a></td>                   
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>

        <div class = "form-group" align="right">
            <a align="right" href="<?= Url::toRoute("evento/nuevo") ?>" class="btn btn-primary">Nuevo Evento</a>
        </div>
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination] ); ?>                      
        </div>
    </div>

