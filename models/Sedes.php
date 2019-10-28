<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sedes".
 *
 * @property int $sede_pk
 * @property string $sede
 * @property int $estado
 *
 * @property Cliente[] $clientes
 */
class Sedes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sedes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sede', 'estado'], 'required'],
            [['estado'], 'integer'],
            [['sede'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sede_pk' => 'Sede Pk',
            'sede' => 'Sede',
            'estado' => 'Estado',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Cliente::className(), ['sede_fk' => 'sede_pk']);
    }
}
