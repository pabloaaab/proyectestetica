<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroFechaInasistentes extends Model
{    
    public $fechaMesAnterior;
    public $fechaMesActual;        

    public function rules()
    {
        return [            
            [['fechaMesAnterior','fechaMesActual'], 'safe'],            
        ];
    }

    public function attributeLabels()
    {
        return [                        
            'fechaMesAnterior' => 'Mes 1:', 
            'fechaMesActual' => 'Mes 2:', 
        ];
    }
}
