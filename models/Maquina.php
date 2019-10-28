<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "maquina".
 *
 * @property int $id_maquina
 * @property string $maquina
 * @property double $duracion
 *
 * @property Eventos[] $eventos
 */
class Maquina extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'maquina';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['duracion'], 'number'],
            [['maquina'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_maquina' => 'Id Maquina',
            'maquina' => 'Maquina',
            'duracion' => 'Duracion',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Eventos::className(), ['maquina' => 'id_maquina']);
    }
}
