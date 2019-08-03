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
            [[ 'nombre1', 'nombre2', 'apellido1', 'apellido2', 'telefono', 'celular', 'direccion', 'email'], 'required'],
            [['sede_fk','identificacion'], 'integer'],
            [['identificacion'], 'string', 'max' => 22],
            [['nombre1', 'nombre2', 'apellido1', 'apellido2', 'telefono', 'celular'], 'string', 'max' => 15],
            [['direccion'], 'string', 'max' => 200],
            [['email'], 'email', 'max' => 40],
            ['email', 'email_existe'],    
            [['identificacion'], 'unique'],
            [['sede_fk'], 'exist', 'skipOnError' => true, 'targetClass' => Sedes::className(), 'targetAttribute' => ['sede_fk' => 'sede_pk']],
        ];
            
    }

    public function attributeLabels()
    {
        return [
            'cliente_pk' => 'Cliente Pk',
            'identificacion' => 'Identificacion',
            'nombre1' => 'Nombre1',
            'nombre2' => 'Nombre2',
            'apellido1' => 'Apellido1',
            'apellido2' => 'Apellido2',
            'telefono' => 'Telefono',
            'celular' => 'Celular',
            'direccion' => 'Direccion',
            'email' => 'Email',
            'sede_fk' => 'Sede Fk',
        ];
    }

    
    public function email_existe($attribute, $params)
    {
        //Buscar el email en la tabla
        $table = Cliente::find()->where("email=:email", [":email" => $this->email])->andWhere("consecutivo!=:consecutivo", [':consecutivo' => $this->consecutivo]);
        //Si el email existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El email ya existe".$this->consecutivo);
        }
    }        
}
