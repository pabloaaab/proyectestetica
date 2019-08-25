<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroFechaCliente extends Model
{    
    public $fechacreacion;
    public $sede_fk;
    public $anio_mes_dia;

    public function rules()
    {
        return [            
            ['fechacreacion', 'safe'],
            ['sede_fk', 'default'],            
            ['anio_mes_dia', 'default'],
        ];
    }

    public function attributeLabels()
    {
        return [                        
            'fechacreacion' => 'Fecha Ingreso:',            
            'anio_mes_dia' => 'Fecha Año-Mes-Día:',
            'sede_fk' => 'Sede:',
        ];
    }
}
