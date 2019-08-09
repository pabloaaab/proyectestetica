<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "formato_autorizacion".
 *
 * @property int $consecutivo
 * @property string $formato
 * @property string $firma
 */
class FormatoAutorizacion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'formato_autorizacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['formato'], 'string'],
            [['firma'], 'string', 'max' => 5400],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'consecutivo' => 'Consecutivo',
            'formato' => 'Formato',
            'firma' => 'Firma',
        ];
    }
}
