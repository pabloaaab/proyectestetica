<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class FormFiltroCliente extends Model
{
    public $identificacion;
    public $nombre1;
    public $apellido1;
    public $nombre2;
    public $apellido2;
    public $telefono;
    public $celular;
    public $email;

    public function rules()
    {
        return [
            ['identificacion', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            ['nombre1', 'match', 'pattern' => '/^[a-záéíóúñ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['nombre2', 'match', 'pattern' => '/^[a-záéíóúñ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['apellido1', 'match', 'pattern' => '/^[a-záéíóúñ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['apellido2', 'match', 'pattern' => '/^[a-záéíóúñ\s]+$/i', 'message' => 'Sólo se aceptan letras'],
            ['telefono', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            ['celular', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan numeros'],
            ['email', 'default'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'identificacion' => 'N° Identificacion:',
            'nombre1' => 'Primer Nombre:',
            'nombre2' => 'Segundo Nombre:',
            'apellido1' => 'Primer Apellido:',
            'apellido2' => 'Segundo Apellido:',
            'telefono' => 'Teléfono:',
            'celular' => 'Celular:',
            'email' => 'Email:',
        ];
    }
}
