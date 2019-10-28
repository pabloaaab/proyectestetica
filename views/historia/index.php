<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Historia';
?>

    <h1>Historia</h1>
<?php $f = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("historia/index"),
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
            <a align="right" href="<?= Url::toRoute("historia/index") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div> 

<?php $f->end() ?>

    <div class = "form-group" align="right">
        <a align="right" href="<?= Url::toRoute("historia/nuevo") ?>" class="btn btn-primary">Nuevo Registro</a>
    </div>    
    <div class="alert alert-info">Registros: <?= $pagination->totalCount ?></div>
    <div class="table-condensed">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Identificación</th>
                <th scope="col">Nombres Completo</th>                
                <th scope="col">Fecha Nac</th>
                <th scope="col">Teléfono</th>
                <th scope="col">Celular</th>                
                <th scope="col">Dirección</th>                
                <th scope="col">Lugar Residencia</th>                
                <th scope="col">Ocupación</th>                
                <th scope="col">Sexo</th>                
                <th scope="col">Edad</th>                                
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>                
                <th scope="row"><?= $val->id_historia ?></th>                
                <td><?= $val->identificacion ?></td>
                <td><?= $val->nombre ?></td>
                <td><?= $val->fecha_nacimiento ?></td>
                <td><?= $val->telefono ?></td>
                <td><?= $val->celular ?></td>
                <td><?= $val->direccion ?></td>
                <td><?= $val->lugar_residencia ?></td>
                <td><?= $val->ocupacion ?></td>
                <td><?= $val->sexo ?></td>
                <td><?= $val->edad ?></td>                                                             
                <td><a href="<?= Url::toRoute(["historia/editar", "id" => $val->id_historia]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>                
                <td><a href="<?= Url::toRoute(["historia/imprimir", "id" => $val->id_historia]) ?>"><img src="svg/si-glyph-print.svg" align="center" width="20px" height="20px" title="Imprimir"></a></td>                   
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>

        <div class = "form-group" align="right">
            <a href="<?= Url::toRoute("historia/nuevo") ?>" class="btn btn-primary">Nuevo Registro</a>
        </div>
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>

