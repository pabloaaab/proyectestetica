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
<script src="calendar/js/jquery.min.js"></script>
<script src="calendar/js/moment.min.js"></script>
<script src="calendar/js/fullcalendar.min.js"></script>

<link href='calendar/css/fullcalendar.min.css' rel='stylesheet' />
<script src="calendar/js/fullcalendar.mn.js"></script>
<script src='calendar/js/locale-all.js'></script>
<script src='calendar/js/fullcalendar/fullcalendar.js'></script>
<script src='calendar/js/fullcalendar/lang-all.js'></script>

<?php $formulario = ActiveForm::begin([
    "method" => "get",
    "action" => Url::toRoute("evento/indexevento"),
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
$maquina = ArrayHelper::map(\app\models\Maquina::find()->where(['<>','id_maquina',0])->all(), 'id_maquina','maquina');
?>    
<?php if ($usuarioperfil == 2) { ?>    
<div class="panel panel-default panel-filters">
    <div class="panel-heading">
        Filtros de busqueda <i class="glyphicon glyphicon-filter"></i>
    </div>
	
    <div class="panel-body" id="filtromatriculas">
        <div class="row" >                                   
            <?= $formulario->field($form, 'sede_fk')->dropDownList($sede,['prompt' => 'Seleccione...' ]) ?>
            <?= $formulario->field($form, 'maquina')->dropDownList($maquina,['prompt' => 'Seleccione...' ]) ?>
        </div>       
        <div class="panel-footer text-right">
            <?= Html::submitButton("Buscar", ["class" => "btn btn-primary"]) ?>
            <a align="right" href="<?= Url::toRoute("evento/indexevento") ?>" class="btn btn-primary">Actualizar</a>
        </div>
    </div>
</div>
<?php } ?>
<div id="calendar"></div>
<script>
   
$('#calendar').fullCalendar({
    lang: 'es',
    header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listWeek'
    },
      defaultDate: '<?php echo date('Y-m-d');?>',      
      buttonIcons: true, // show the prev/next text
      weekNumbers: true,
      navLinks: true, // can click day/week names to navigate views
      editable: true,
      
    events: [
        <?php foreach ($events as $event): ?>
        {
            id:    <?php echo "'".$event->id."'";?>,
            title: <?php echo "'".$event->asunto." - ".$event->nombres." - ".$event->sede_fk."'";?>,
            start: <?php echo "'".$event->fechai."'";?>,
            end:   <?php echo "'".$event->fechat."'";?>,
            color: <?php echo "'".$event->color."'";?>
        },
        <?php endforeach; ?>
    ]
});
</script>
<?php $formulario->end() ?>