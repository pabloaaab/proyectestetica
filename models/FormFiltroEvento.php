<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroEvento extends Model
{
    public $identificacion;    
    public $cliente;
    public $fecha;
    public $maquina;
    public $sede_fk;
    public $anio_mes_dia;

    public function rules()
    {
        return [
            ['identificacion', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            ['cliente', 'match', 'pattern' => '/^[a-záéíóúñ\s]+$/i', 'message' => 'Sólo se aceptan letras'],            
            ['fecha', 'default'],
            ['maquina', 'default'],
            ['sede_fk', 'default'],
            ['anio_mes_dia', 'default'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'N° Identificacion:',
            'cliente' => 'Cliente:',            
            'fecha' => 'Fecha:',
            'maquina' => 'Maquina:',
            'sede_fk' => 'Sede:',
            'anio_mes_dia' => 'Fecha Año-Mes-Día:',
        ];
    }
}
