<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

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
<div class="panel panel-default panel-filters">
    <div class="panel-heading panel-x">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtroevento">
        <div class="row" >
            <?= $f->field($form, "identificacion")->input("search") ?>
            <?= $f->field($form, "fecha")->input("search") ?>
            <?= $f->field($form, "cliente")->input("search") ?>
            <?= $f->field($form, "maquina")->input("search") ?>
            <?= $f->field($form, "sede_fk")->input("search") ?>            
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
                <th scope="col">Profesional</th>                
                <th scope="col">Maquina</th>
                <th scope="col">Telefono</th>
                <th scope="col">Observaciones</th>
                <th scope="col">Cancelo?</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>                                                
                <td><?= $val->id ?></td>
                <td><?= $val->fechai ?></td>
                <td><?= $val->fechai ?></td>
                <td><?= $val->fechai ?></td>
                <td><?= $val->asunto ?></td>
                <td><?= $val->nombres ?></td>
                <td><?= $val->identificacion ?></td>
                <td><?= $val->sede_fk ?></td>
                <td><?= $val->id_profesional ?></td>
                <td><?= $val->maquina ?></td>
                <td><?= $val->telefono ?></td>
                <td><?= $val->observaciones ?></td>
                <td><?= $val->cancelo_no_asistio ?></td>                                
                <td><a href="<?= Url::toRoute(["evento/editar", "id" => $val->id]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>                   
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

