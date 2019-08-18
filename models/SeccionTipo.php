<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "seccion_tipo".
 *
 * @property string $seccion_tipo_pk
 * @property string $tipo
 * @property int $estado
 */
class SeccionTipo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'seccion_tipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tipo'], 'required'],
            //[['estado'], 'integer'],
            [['tipo'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'seccion_tipo_pk' => 'Id',
            'tipo' => 'Tipo',
            //'estado' => 'Estado',
        ];
    }
}
