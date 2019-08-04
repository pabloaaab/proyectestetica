<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Clientes';
?>

    <h1>Clientes</h1>
<?php $f = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("cliente/index"),
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
            <?= $f->field($form, "identificacion")->input("search") ?>
            <?= $f->field($form, "email")->input("search") ?>
            <?= $f->field($form, "nombre1")->input("search") ?>
            <?= $f->field($form, "nombre2")->input("search") ?>
            <?= $f->field($form, "apellido1")->input("search") ?>
            <?= $f->field($form, "apellido2")->input("search") ?>
            <?= $f->field($form, "telefono")->input("search") ?>
            <?= $f->field($form, "celular")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("cliente/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div> 

<?php $f->end() ?>

    <div class = "form-group" align="right">
        <a align="right" href="<?= Url::toRoute("cliente/nuevo") ?>" class="btn btn-primary">Nuevo Cliente</a>
    </div>    
    <div class="alert alert-info">Registros: <?= $pagination->totalCount ?></div>
    <div class="table-condensed">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Id</th>                
                <th scope="col">Identificación</th>
                <th scope="col">Nombre_1</th>
                <th scope="col">Nombre_2</th>
                <th scope="col">Apellido_1</th>
                <th scope="col">Apellido_2</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Celular</th>
                <th scope="col">Email</th>                
                <th scope="col">Dirección</th>                
                <th scope="col">Sede</th>                                
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>                                                
                <td><?= $val->cliente_pk ?></td>
                <td><?= $val->identificacion ?></td>
                <td><?= $val->nombre1 ?></td>
                <td><?= $val->nombre2 ?></td>
                <td><?= $val->apellido1 ?></td>
                <td><?= $val->apellido2 ?></td>
                <td><?= $val->telefono ?></td>
                <td><?= $val->celular ?></td>
                <td><?= $val->email ?></td>
                <td><?= $val->direccion ?></td>
                <td><?= $val->sedeFk->sede ?></td>                
                <td><a href="<?= Url::toRoute(["cliente/editar", "cliente_pk" => $val->cliente_pk]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>                   
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>

        <div class = "form-group" align="right">
            <a align="right" href="<?= Url::toRoute("cliente/nuevo") ?>" class="btn btn-primary">Nuevo Cliente</a>
        </div>
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination] ); ?>                      
        </div>
    </div>

