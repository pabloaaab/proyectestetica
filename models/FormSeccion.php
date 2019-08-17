<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Seccion;

/**
 * ContactForm is the model behind the contact form.
 */
class FormSeccion extends Model
{    
    public $cliente_fk;
    public $sede_fk;
    public $seccion_tipo_fk;    
    public $fecha_seccion;
    public $hora_seccion;    
    public $valor_seccion;
    public $observaciones;

    public function rules()
    {
        return [

            [['cliente_fk', 'sede_fk', 'seccion_tipo_fk', 'fecha_seccion','hora_seccion','valor_seccion'], 'required', 'message' => 'Campo requerido'],            
            [['valor_seccion'], 'number'],
            [['observaciones'], 'default'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'cliente_fk' => 'Cliente:',
            'sede_fk' => 'Sede:',
            'seccion_tipo_fk' => 'Seccion:',
            'fecha_seccion' => 'Fecha Sección:',
            'hora_seccion' => 'Hora Sección:',  
            'valor_seccion' => 'Valor Sección:', 
            'observaciones' => 'Observaciones:', 
        ];
    }        

}
