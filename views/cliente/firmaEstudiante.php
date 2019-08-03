<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

$this->title = 'Subir Firma Estudiante';
?>

<h1>Subir Firma Estudiante</h1>
<h3 class="alert-danger"><?= $msg ?></h3>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>
<?= $form->field($model, 'consecutivo')->input("hidden") ?>
<?= $form->field($model, 'imageFile')->fileInput() ?>

<div class="row">
    <div class="col-lg-4">
        <?= Html::submitButton("Subir Archivo", ["class" => "btn btn-primary"])?>
        <a href="<?= Url::toRoute("inscritos/index") ?>" class="btn btn-primary">Regresar</a>
    </div>
</div>

<?php ActiveForm::end() ?>


