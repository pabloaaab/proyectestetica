<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "profesionales".
 *
 * @property int $id_profesional
 * @property int $identificacion
 * @property string $nombre
 * @property int $estado
 *
 * @property Eventos[] $eventos
 */
class Profesionales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'profesionales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identificacion', 'nombre'], 'required'],
            [['identificacion', 'estado'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_profesional' => 'Id Profesional',
            'identificacion' => 'Identificacion',
            'nombre' => 'Nombre',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Eventos::className(), ['id_profesional' => 'id_profesional']);
    }
}
