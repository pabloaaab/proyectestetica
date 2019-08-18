<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroInformesPagos extends Model
{
    public $identificacion;    
    public $fecha_pago;
    public $sede_fk;    
    public $anio_mes_dia;
    public $seccion_pk;

    public function rules()
    {
        return [

            ['identificacion', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            ['seccion_pk', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            //['nivel', 'match', 'pattern' => '/^[a-z0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros y letras'],
            ['fecha_pago', 'safe'],            
            ['sede_fk', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],            
            ['anio_mes_dia', 'match', 'pattern' => '/^[a-z\s]+$/i', 'message' => 'Sólo se aceptan letras'],            
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'Nro identificación:',            
            'fecha_pago' => 'Fecha Dia:',
            'sede_fk' => 'Sede:',            
            'anio_mes_dia' => 'Fecha Año-Mes-Día:',
            'seccion_pk' => 'Nro Pago:',
        ];
    }
}
