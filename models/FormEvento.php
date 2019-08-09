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
        
    public $id;
    public $asunto;
    public $identificacion;
    public $fechai;
    public $fechat;
    public $color;
    public $sede_fk;
    public $nombres;
    public $seccion_tipo_fk;
    public $cancelo_no_asistio;
    public $id_profesional;
    public $maquina;
    public $observaciones;    

    public function rules()
    {                                    
        return [
            [['asunto', 'sede_fk', 'nombres', 'identificacion', 'seccion_tipo_fk', 'maquina'], 'required'],            
            [['asunto', 'observaciones'], 'string'],
            [['fechai', 'fechat'], 'safe'],
            [['sede_fk', 'seccion_tipo_fk', 'cancelo_no_asistio', 'id_profesional', 'maquina'], 'integer'],
            [['color'], 'string', 'max' => 150],
            [['nombres'], 'string', 'max' => 60],
            [['telefono'], 'string', 'max' => 40],
            [['identificacion'], 'string', 'max' => 25],
            [['sede_fk'], 'exist', 'skipOnError' => true, 'targetClass' => Sedes::className(), 'targetAttribute' => ['sede_fk' => 'sede_pk']],
            [['seccion_tipo_fk'], 'exist', 'skipOnError' => true, 'targetClass' => SeccionTipo::className(), 'targetAttribute' => ['seccion_tipo_fk' => 'seccion_tipo_pk']],
            [['maquina'], 'exist', 'skipOnError' => true, 'targetClass' => Maquina::className(), 'targetAttribute' => ['maquina' => 'id_maquina']],
            [['id_profesional'], 'exist', 'skipOnError' => true, 'targetClass' => Profesionales::className(), 'targetAttribute' => ['id_profesional' => 'id_profesional']],
        ];
            
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'asunto' => 'Asunto',
            'fechai' => 'Fechai',
            'fechat' => 'Fechat',
            'color' => 'Color',
            'sede_fk' => 'Sede Fk',
            'nombres' => 'Nombres',
            'seccion_tipo_fk' => 'Seccion Tipo Fk',
            'cancelo_no_asistio' => 'Cancelo No Asistio',
            'id_profesional' => 'Id Profesional',
            'maquina' => 'Maquina',
            'observaciones' => 'Observaciones',
            'telefono' => 'Telefono',
            'identificacion' => 'Identificacion',
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
