<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "consentimiento_cliente".
 *
 * @property int $consentimiento_cliente_pk
 * @property string $nombre
 * @property string $identificacion
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $fototipo_area
 * @property string $fototipo_piel
 * @property int $sede_fk
 * @property int $consentimiento
 * @property string $firma
 * @property string $fechaconsentimiento
 *
 * @property Sedes $sedeFk
 */
class ConsentimientoCliente extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consentimiento_cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['consentimiento_cliente_pk', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            [['identificacion'], 'required'],            
            ['identificacion', 'identificacion_existe'],
            ['identificacion', 'identificacion_no_existe'],
            [['fecha_creacion', 'fecha_modificacion', 'fechaconsentimiento'], 'safe'],
            [['sede_fk', 'consentimiento'], 'integer'],
            [['nombre'], 'string', 'max' => 100],
            [['identificacion'], 'string', 'max' => 22],
            [['fototipo_area', 'fototipo_piel'], 'string', 'max' => 300],
            [['firma'], 'string', 'max' => 900],
            [['sede_fk'], 'exist', 'skipOnError' => true, 'targetClass' => Sedes::className(), 'targetAttribute' => ['sede_fk' => 'sede_pk']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'consentimiento_cliente_pk' => '',
            'nombre' => 'Nombre',
            'identificacion' => 'Identificacion',
            'fecha_creacion' => 'Fecha Creacion',
            'fecha_modificacion' => 'Fecha Modificacion',
            'fototipo_area' => 'Fototipo Area',
            'fototipo_piel' => 'Fototipo Piel',
            'sede_fk' => 'Sede',
            'consentimiento' => 'Consentimiento',
            'firma' => 'Firma',
            'fechaconsentimiento' => 'Fecha Consentimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSedeFk()
    {
        return $this->hasOne(Sedes::className(), ['sede_pk' => 'sede_fk']);
    }        
    
    public function getConsentimientos()
    {
        if($this->consentimiento == 1){
            $consentimiento = "SI";
        }else{
            $consentimiento = "NO";
        }
        return $consentimiento;
    }
    
    public function getFirmo()
    {
        if($this->firma <> 0){
            $firmo = "SI";
        }else{
            $firmo = "NO";
        }
        return $firmo;
    }
    
    public function identificacion_existe($attribute, $params)
    {
        //Buscar la identificacion en la tabla
        $table = ConsentimientoCliente::find()->where("identificacion=:identificacion", [":identificacion" => $this->identificacion])->andWhere("consentimiento_cliente_pk!=:consentimiento_cliente_pk", [':consentimiento_cliente_pk' => $this->consentimiento_cliente_pk]);
        //Si la identificacion existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El número de identificación ya existe ".$this->identificacion);
        }
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
