<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Cliente;

/**
 * ContactForm is the model behind the contact form.
 */
class FormEvento extends Model
{
        
    public $id;
    public $asunto;
    public $identificacion;
    public $fechai;        
    public $horai;                   
    public $sede_fk;     
    public $id_profesional;
    public $maquina;
    public $telefono;
    public $observaciones;    

    public function rules()
    {                                    
        return [
            [['asunto', 'sede_fk', 'identificacion', 'maquina','fechai','horai','id_profesional'], 'required'],            
            [['asunto', 'observaciones'], 'string'],
            [['fechai', 'fechat'], 'safe'],
            ['id' , 'default'],
            [['sede_fk', 'id_profesional', 'maquina'], 'integer'],                       
            [['telefono'], 'string', 'max' => 40],
            [['identificacion'], 'string', 'max' => 25],
            ['identificacion', 'identificacion_no_existe'],            
            [['sede_fk'], 'exist', 'skipOnError' => true, 'targetClass' => Sedes::className(), 'targetAttribute' => ['sede_fk' => 'sede_pk']],            
            [['maquina'], 'exist', 'skipOnError' => true, 'targetClass' => Maquina::className(), 'targetAttribute' => ['maquina' => 'id_maquina']],
            [['id_profesional'], 'exist', 'skipOnError' => true, 'targetClass' => Profesionales::className(), 'targetAttribute' => ['id_profesional' => 'id_profesional']],
        ];
            
    }

    public function attributeLabels()
    {
        return [
            'id' => '',
            'asunto' => 'Asunto',
            'fechai' => 'Fecha Consulta',                        
            'sede_fk' => 'Sedes',                                   
            'id_profesional' => 'Profesional',
            'maquina' => 'Maquina',
            'observaciones' => 'Observaciones',
            'telefono' => 'Telefono',
            'identificacion' => 'Paciente/Cliente',
            'horai' => 'Hora Consulta',
        ];
    }
    
    public function identificacion_no_existe($attribute, $params)
    {
        //Buscar la cedula/nit en la tabla
        $table = Cliente::find()->where("identificacion=:identificacion", [":identificacion" => $this->identificacion]);
        //Si la identificacion no existe en Cliente mostrar el error
        if ($table->count() == 0)
        {
            $this->addError($attribute, "El número de identificación No existe en clientes, por favor realizar la inscripción");
        }
    }
    
    
    
    
                
}
