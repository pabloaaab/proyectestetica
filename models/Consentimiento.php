<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "consentimiento".
 *
 * @property int $consecutivo
 * @property string $formato
 * @property string $firma
 */
class Consentimiento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consentimiento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['formato'], 'string'],
            [['firma'], 'string', 'max' => 1800],
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
