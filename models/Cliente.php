<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente".
 *
 * @property int $cliente_pk
 * @property string $identificacion
 * @property string $nombre1
 * @property string $nombre2
 * @property string $apellido1
 * @property string $apellido2
 * @property string $telefono
 * @property string $celular
 * @property string $direccion
 * @property string $email
 * @property int $sede_fk
 *
 * @property Sedes $sedeFk
 */
class Cliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente';
    }
    
    public function beforeSave($insert) {
	if(!parent::beforeSave($insert)){
            return false;
        }
	# ToDo: Cambiar a cliente cargada de configuración.    
	$this->nombre1 = strtoupper($this->nombre1);
        $this->nombre2 = strtoupper($this->nombre2);
	$this->apellido1 = strtoupper($this->apellido1);
        $this->apellido2 = strtoupper($this->apellido2);
        $this->direccion = strtoupper($this->direccion);
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre1', 'nombre2', 'apellido1', 'apellido2', 'telefono', 'celular', 'direccion', 'email'], 'required'],
            [['sede_fk','identificacion','cliente_pk'], 'integer'],
            ['identificacion', 'identificacion_existe'],
            [['nombre1', 'nombre2', 'apellido1', 'apellido2', 'telefono', 'celular'], 'string', 'max' => 15],
            [['direccion'], 'string', 'max' => 200],
            [['email'], 'email'],                        
            [['sede_fk'], 'exist', 'skipOnError' => true, 'targetClass' => Sedes::className(), 'targetAttribute' => ['sede_fk' => 'sede_pk']],
        ];
    }

    /**
     * {@inheritdoc}
     */
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSedeFk()
    {
        return $this->hasOne(Sedes::className(), ['sede_pk' => 'sede_fk']);
    }        
        
}
