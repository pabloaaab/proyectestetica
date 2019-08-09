<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Habeasdata';
?>

    <h1>Habeasdata</h1>
<?php $f = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("habeasdata/index"),
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
	
    <div class="panel-body" id="filtrohabeasdata">
        <div class="row" >
            <?= $f->field($form, "q")->input("search") ?>
        </div>
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("habeasdata/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div> 

<?php $f->end() ?>

    <div class = "form-group" align="right">
        <a align="right" href="<?= Url::toRoute("habeasdata/nuevo") ?>" class="btn btn-primary">Nuevo Registro</a>
    </div>    
    <div class="alert alert-info">Registros: <?= $pagination->totalCount ?></div>
    <div class="table-condensed">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Identificación</th>
                <th scope="col">Nombres Completo</th>                
                <th scope="col">Sede</th>
                <th scope="col">Autoriza</th>
                <th scope="col">Firma</th>                
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>
                <?php if ($val->firma != "") {$firma = "SI"; } else {$firma = "NO"; } ?>
                <?php if ($val->autorizacion == 1) {$autoriza = "SI"; } else {$autoriza = "NO"; } ?>
                <th scope="row"><?= $val->id ?></th>                
                <td><?= $val->identificacion ?></td>
                <td><?= $val->nombre ?></td>
                <td><?= $val->sedeFk->sede ?></td>
                <td><?= $autoriza ?></td>
                <td><?= $firma ?></td>                                                
                <td><a href="<?= Url::toRoute(["habeasdata/editar", "id" => $val->id]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>
                <td><a href="<?= Url::toRoute(["habeasdata/firma_cliente", "id" => $val->id]) ?>" ><img src="svg/si-glyph-pencil.svg" align="center" width="20px" height="20px" title="Firma Cliente"></a></td>                
                <?php if ($val->autorizacion == 1 && $val->firma != "") { ?>
                <td><a href="<?= Url::toRoute(["habeasdata/imprimir", "id" => $val->id]) ?>"><img src="svg/si-glyph-print.svg" align="center" width="20px" height="20px" title="Imprimir"></a></td>
                <?php } else { ?>
                    <td></td>
                <?php } ?>    
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>

        <div class = "form-group" align="right">
            <a href="<?= Url::toRoute("habeasdata/nuevo") ?>" class="btn btn-primary">Nuevo Registro</a>
        </div>
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>

