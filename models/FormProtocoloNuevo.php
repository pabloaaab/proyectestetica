<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormProtocoloNuevo extends Model
{    
    public $fecha;
    public $pieza_mano;
    public $potencia_tiempo;
    public $energia;
    public $area;
    public $pases;  
    
    public function rules()
    {
        return [            
            [['fecha'], 'required'],            
            [['pieza_mano','potencia_tiempo','energia','area','pases'], 'string'],                       
        ];
    }

    public function attributeLabels()
    {
        return [                        
            'fecha' => 'Fecha:',   
            'pieza_mano' => 'Pieza Mano:',
            'potencia_tiempo' => 'Potencia Tiempo:',
            'energia' => 'Energia:',
            'area' => 'Area:',
            'pases' => 'Pases:',
            
        ];
    }
    
}
