<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Cliente;

/**
 * ContactForm is the model behind the contact form.
 */
class FormCliente extends Model
{
        
    public $cliente_pk;
    public $identificacion;
    public $nombre1;
    public $nombre2;
    public $apellido1;
    public $apellido2;
    public $telefono;
    public $celular;
    public $direccion;
    public $email;
    public $sede_fk;

    public function rules()
    {                    
        return [
            [['nombre1', 'apellido1', 'celular'], 'required'],            
            [['sede_fk','identificacion','cliente_pk'], 'integer'], 
            ['identificacion', 'identificacion_existe'],
            [['nombre1', 'nombre2', 'apellido1', 'apellido2', 'telefono', 'celular'], 'string'],
            [['direccion'], 'string'],
            [['email'], 'email'],            
            [['sede_fk'], 'exist', 'skipOnError' => true, 'targetClass' => Sedes::className(), 'targetAttribute' => ['sede_fk' => 'sede_pk']],
        ];
            
    }

    public function attributeLabels()
    {
        return [
            'cliente_pk' => '',
            'identificacion' => 'Identificacion',
            'nombre1' => 'Nombre1',
            'nombre2' => 'Nombre2',
            'apellido1' => 'Apellido1',
            'apellido2' => 'Apellido2',
            'telefono' => 'Telefono',
            'celular' => 'Celular',
            'direccion' => 'Direccion',
            'email' => 'Email',
            'sede_fk' => 'Sede',
        ];
    }

    public function identificacion_existe($attribute, $params)
    {
        //Buscar la identificacion en la tabla
        $table = Cliente::find()->where("identificacion=:identificacion", [":identificacion" => $this->identificacion])->andWhere("cliente_pk!=:cliente_pk", [':cliente_pk' => $this->cliente_pk]);
        //Si la identificacion existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El número de identificación ya existe".$this->identificacion);
        }
    }
                
}
