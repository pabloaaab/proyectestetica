<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroSeccion extends Model
{
    public $identificacion;

    public function rules()
    {
        return [

            ['identificacion', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'N° Identificación:',
        ];
    }
}
