<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seccion".
 *
 * @property int $seccion_pk
 * @property int $servicio_fk
 * @property int $seccion_tipo_fk
 * @property string $fecha
 * @property double $valor_seccion
 * @property string $usuario
 * @property string $fecha_abono
 * @property double $valor_abono
 * @property string $estado_pagado
 * @property double $total_pago
 * @property string $fecha_pago
 * @property string $estado_abono
 * @property string $fecha_anulado
 * @property string $estado_anulado
 * @property string $usuario_anulado
 * @property string $observaciones
 * @property string $hora_seccion
 * @property string $usuario_pago
 * @property int $sede_fk
 * @property int $centro_costo
 * @property int $cliente_fk
 *
 * @property SeccionTipo $seccionTipoFk
 * @property Sedes $sedeFk
 * @property Cliente $clienteFk
 */
class Seccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seccion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['servicio_fk', 'seccion_tipo_fk', 'fecha', 'valor_seccion', 'usuario', 'fecha_abono', 'valor_abono', 'estado_pagado', 'total_pago', 'fecha_pago', 'estado_abono', 'fecha_anulado', 'estado_anulado', 'usuario_anulado', 'observaciones', 'hora_seccion'], 'required'],
            [['servicio_fk', 'seccion_tipo_fk', 'sede_fk', 'centro_costo', 'cliente_fk'], 'integer'],
            [['fecha', 'fecha_abono', 'fecha_pago', 'fecha_anulado', 'hora_seccion'], 'safe'],
            [['valor_seccion', 'valor_abono', 'total_pago'], 'number'],
            [['observaciones'], 'string'],
            [['usuario', 'usuario_anulado', 'usuario_pago'], 'string', 'max' => 120],
            [['estado_pagado', 'estado_abono', 'estado_anulado'], 'string', 'max' => 2],
            [['seccion_tipo_fk'], 'exist', 'skipOnError' => true, 'targetClass' => SeccionTipo::className(), 'targetAttribute' => ['seccion_tipo_fk' => 'seccion_tipo_pk']],
            [['sede_fk'], 'exist', 'skipOnError' => true, 'targetClass' => Sedes::className(), 'targetAttribute' => ['sede_fk' => 'sede_pk']],
            [['cliente_fk'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['cliente_fk' => 'cliente_pk']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'seccion_pk' => 'Seccion Pk',
            'servicio_fk' => 'Servicio Fk',
            'seccion_tipo_fk' => 'Seccion Tipo Fk',
            'fecha' => 'Fecha',
            'valor_seccion' => 'Valor Seccion',
            'usuario' => 'Usuario',
            'fecha_abono' => 'Fecha Abono',
            'valor_abono' => 'Valor Abono',
            'estado_pagado' => 'Estado Pagado',
            'total_pago' => 'Total Pago',
            'fecha_pago' => 'Fecha Pago',
            'estado_abono' => 'Estado Abono',
            'fecha_anulado' => 'Fecha Anulado',
            'estado_anulado' => 'Estado Anulado',
            'usuario_anulado' => 'Usuario Anulado',
            'observaciones' => 'Observaciones',
            'hora_seccion' => 'Hora Seccion',
            'usuario_pago' => 'Usuario Pago',
            'sede_fk' => 'Sede Fk',
            'centro_costo' => 'Centro Costo',
            'cliente_fk' => 'Cliente Fk',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeccionTipoFk()
    {
        return $this->hasOne(SeccionTipo::className(), ['seccion_tipo_pk' => 'seccion_tipo_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSedeFk()
    {
        return $this->hasOne(Sedes::className(), ['sede_pk' => 'sede_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClienteFk()
    {
        return $this->hasOne(Cliente::className(), ['cliente_pk' => 'cliente_fk']);
    }
}
