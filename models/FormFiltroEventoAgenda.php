<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroEventoAgenda extends Model
{    
    public $maquina;
    public $sede_fk;    

    public function rules()
    {
        return [            
            ['maquina', 'default'],
            ['sede_fk', 'default'],            
        ];
    }

    public function attributeLabels()
    {
        return [            
            'maquina' => 'Maquina:',
            'sede_fk' => 'Sede:',            
        ];
    }
}
