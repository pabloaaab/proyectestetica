<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\seccion;

/**
 * ContactForm is the model behind the contact form.
 */
class FormSeccionAnula extends Model
{        
    public $fechaanulado;              

    public function rules()
    {
        return [
            [['fechaanulado'], 'required', 'message' => 'Campo requerido'],                                                        
        ];
    }

    public function attributeLabels()
    {
        return [
            'fechaanulado' => 'Fecha Anulación:',                        
        ];
    }        
}
