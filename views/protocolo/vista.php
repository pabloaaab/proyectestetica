<?php

use yii\helpers\Html;
use app\models\Protocolo;
use app\models\ConsentimientoCliente;
use yii\helpers\Url;
use yii\web\Session;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;

/* @var $this yii\web\View */
/* @var $model app\models\ConsentimientoCliente */
/* @var $model app\models\Protocolo */

$this->title = 'Detalle Protocolos';
$this->params['breadcrumbs'][] = ['label' => 'Protocolos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->consentimiento_cliente_pk;

?>
<div class="protocolo-view">

    <!--<h1><?= Html::encode($this->title) ?></h1>-->

    <p>
        <?= Html::a('<span class="glyphicon glyphicon-circle-arrow-left"></span> Regresar', ['index', 'id' => $model->consentimiento_cliente_pk], ['class' => 'btn btn-primary']) ?>        
    </p>
    <?php
    if ($mensaje != ""){
        ?> <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $mensaje ?>
        </div> <?php
    }
    ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5><?= Html::encode($this->title) ?></h5>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-hover">
                <tr>
                    <th><?= Html::activeLabel($model, 'id') ?>:</th>
                    <td><?= Html::encode($model->consentimiento_cliente_pk) ?></td>
                    <th><?= Html::activeLabel($model, 'identificacion') ?>:</th>
                    <td><?= Html::encode($model->identificacion) ?></td>
                    <th><?= Html::activeLabel($model, 'nombre') ?>:</th>
                    <td><?= Html::encode($model->nombre) ?></td>
                </tr>
                <tr>
                    <th><?= Html::activeLabel($model, 'sede_fk') ?>:</th>
                    <td><?= Html::encode($model->sedeFk->sede) ?></td>
                    <th><?= Html::activeLabel($model, 'fototipo_area') ?>:</th>
                    <td><?= Html::encode($model->fototipo_area) ?></td>
                    <th><?= Html::activeLabel($model, 'fototipo_piel') ?>:</th>
                    <td><?= Html::encode($model->fototipo_piel) ?></td>
                </tr>
                <tr>
                    <th><?= Html::activeLabel($model, 'consentimiento') ?>:</th>
                    <td><?= Html::encode($model->consentimientos) ?></td>
                    <th><?= Html::activeLabel($model, 'firma') ?>:</th>
                    <td><?= Html::encode($model->firmo) ?></td>
                    <th><?= Html::activeLabel($model, 'fechaconsentimiento') ?>:</th>
                    <td><?= Html::encode($model->fechaconsentimiento) ?></td>
                </tr>                
            </table>
        </div>
    </div>
    <div class="table-responsive">
        <div class="panel panel-default ">
            <div class="panel-heading">
                Protocolos
            </div>
            <div class="panel-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Pieza Mano</th>
                        <th scope="col">Potencia Tiempo</th>
                        <th scope="col">Energia</th>
                        <th scope="col">Area</th>
                        <th scope="col">Pases</th>                        
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($protocolos as $val): ?>
                    <tr>
                        <td><?= $val->id_protocolo ?></td>
                        <td><?= $val->fecha ?></td>
                        <td><?= $val->pieza_mano ?></td>
                        <td><?= $val->potencia_tiempo ?></td>
                        <td><?= $val->energia ?></td>
                        <td><?= $val->area ?></td>
                        <td><?= $val->pases ?></td>                        
                        <td>
                            <a href="#" data-toggle="modal" data-target="#iddetalle1<?= $val->id_protocolo ?>"><span class="glyphicon glyphicon-pencil"></span></a>
                            <!-- Editar modal detalle -->
                            <div class="modal fade" role="dialog" aria-hidden="true" id="iddetalle1<?= $val->id_protocolo ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title">Editar protocolo <?= $val->id_protocolo ?></h4>
                                        </div>
                                        <?= Html::beginForm(Url::toRoute("protocolo/editarprotocolo"), "POST") ?>
                                        <div class="modal-body">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4>Editar Protocolo</h4>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="col-lg-6">
                                                        <label>Fecha:</label>                                                                                                        
                                                        <input type="date" name="fecha" value="<?= $val->fecha ?>" class="form-control" required>                                                    
                                                        <label>Pieza Mano:</label>                                                    
                                                        <input type="text" name="pieza_mano" value="<?=  $val->pieza_mano ?>" class="form-control">                                                    
                                                        <label>Potencia Tiempo:</label>                                                    
                                                        <input type="text" name="potencia_tiempo" value="<?=  $val->potencia_tiempo ?>" class="form-control">                                                    
                                                        <label>Energia:</label>                                                    
                                                        <input type="text" name="energia" value="<?=  $val->energia ?>" class="form-control">                                                   
                                                        <label>Area:</label>                                                    
                                                        <input type="text" name="area" value="<?=  $val->area ?>" class="form-control">                                                    
                                                        <label>Pases:</label>                                                    
                                                        <input type="text" name="pases" value="<?=  $val->pases ?>" class="form-control">
                                                    </div>
                                                    <input type="hidden" name="iddetalle" value="<?= $val->id_protocolo ?>">
                                                    <input type="hidden" name="consentimiento_cliente_pk" value="<?= $model->consentimiento_cliente_pk ?>">                                                    
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-warning" data-dismiss="modal"><span class='glyphicon glyphicon-remove'></span> Cerrar</button>
                                            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Guardar</button>
                                        </div>
                                        <?= Html::endForm() ?>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                            <!-- Eliminar modal detalle -->
                            <a href="#" data-toggle="modal" data-target="#iddetalle2<?= $val->id_protocolo ?>"><span class="glyphicon glyphicon-trash"></span></a>
                            <div class="modal fade" role="dialog" aria-hidden="true" id="iddetalle2<?= $val->id_protocolo ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                            <h4 class="modal-title">Eliminar Protocolo</h4>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Realmente deseas eliminar el registro con código <?= $val->id_protocolo ?>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <?= Html::beginForm(Url::toRoute("protocolo/eliminarprotocolo"), "POST") ?>
                                            <input type="hidden" name="iddetalle" value="<?= $val->id_protocolo ?>">
                                            <input type="hidden" name="consentimiento_cliente_pk" value="<?= $model->consentimiento_cliente_pk ?>">
                                            <button type="button" class="btn btn-warning" data-dismiss="modal"><span class='glyphicon glyphicon-remove'></span> Cerrar</button>
                                            <button type="submit" class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span> Eliminar</button>
                                            <?= Html::endForm() ?>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </td>                        
                    </tr>
                    </tbody>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="panel-footer text-right">                    
                <!-- Inicio Nuevo Detalle proceso -->
                <?= Html::a('<span class="glyphicon glyphicon-plus"></span> Nuevo Protocolo',
                    ['/protocolo/nuevoprotocolo','id' => $model->consentimiento_cliente_pk],
                    [
                        'title' => 'Nuevo Protocolo',
                        'data-toggle'=>'modal',
                        'data-target'=>'#modalnuevoprotocolo',
                        'class' => 'btn btn-primary'
                    ])                                    

                ?>
                <div class="modal remote fade" id="modalnuevoprotocolo">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content"></div>
                    </div>
                </div>
                <!-- Fin Nuevo Detalle proceso -->
                                       
                </div>
        </div>
    </div>
</div>
