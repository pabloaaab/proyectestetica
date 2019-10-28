<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\ContactForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Sedes';
?>

    <h1>Sedes</h1>
<?php $f = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("sede/index"),
    "enableClientValidation" => true,
]);
?>

<div class="form-group">
    <?= $f->field($form, "q")->input("search") ?>
</div>

<div class="row" >
    <div class="col-lg-4">
        <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
        <a align="right" href="<?= Url::toRoute("sede/index") ?>" class="btn btn-primary">Actualizar</a>
    </div>
</div>
<?php $f->end() ?>

<h3><?= $search ?></h3>

    <div class = "form-group" align="right">
        <a align="right" href="<?= Url::toRoute("sede/nuevo") ?>" class="btn btn-primary">Nuevo Sede</a>
    </div>

<div class="alert alert-info">Registros: <?= $pagination->totalCount ?></div>    

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Sede</th>                
            </tr>
            </thead>
            <tbody>
            <?php foreach ($model as $val): ?>
            <tr>
                <th scope="row"><?= $val->sede_pk ?></th>
                <td><?= $val->sede ?></td>                
                <td><a href="<?= Url::toRoute(["sede/editar", "sede_pk" => $val->sede_pk]) ?>" ><img src="svg/si-glyph-document-edit.svg" align="center" width="20px" height="20px" title="Editar"></a></td>
            </tr>
            </tbody>
            <?php endforeach; ?>
        </table>

        <div class = "form-group" align="right">
            <a href="<?= Url::toRoute("sede/nuevo") ?>" class="btn btn-primary">Nuevo Sede</a>
        </div>
        <div class = "form-group" align="left">
            <?= LinkPager::widget(['pagination' => $pagination]) ?>
        </div>
    </div>
