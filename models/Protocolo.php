<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "protocolo".
 *
 * @property int $id_protocolo
 * @property string $fecha
 * @property string $pieza_mano
 * @property string $potencia_tiempo
 * @property string $energia
 * @property string $area
 * @property string $pases
 * @property int $consentimiento_cliente_fk
 */
class Protocolo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'protocolo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha'], 'safe'],
            [['consentimiento_cliente_fk'], 'integer'],
            [['pieza_mano'], 'string', 'max' => 80],
            [['potencia_tiempo', 'energia', 'area'], 'string', 'max' => 50],
            [['pases'], 'string', 'max' => 5],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_protocolo' => 'Id Protocolo',
            'fecha' => 'Fecha',
            'pieza_mano' => 'Pieza Mano',
            'potencia_tiempo' => 'Potencia Tiempo',
            'energia' => 'Energia',
            'area' => 'Area',
            'pases' => 'Pases',
            'consentimiento_cliente_fk' => 'Consentimiento Cliente Fk',
        ];
    }
}
