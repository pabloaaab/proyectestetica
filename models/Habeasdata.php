<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "habeasdata".
 *
 * @property int $id
 * @property string $nombre
 * @property int $identificacion
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property string $sede_fk
 * @property int $autorizacion
 * @property string $firma
 * @property string $fechaautorizacion
 *
 * @property Sedes $sedeFk
 */
class Habeasdata extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'habeasdata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['id', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            [['identificacion', 'sede_fk', 'autorizacion'], 'integer'],
            ['identificacion', 'identificacion_existe'],
            ['identificacion', 'identificacion_no_existe'],
            [['fecha_creacion', 'fecha_modificacion', 'fechaautorizacion'], 'safe'],
            [['sede_fk','identificacion','fechaautorizacion','autorizacion'], 'required'],            
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
            'id' => '',            
            'identificacion' => 'Identificación:',
            'fecha_creacion' => 'Fecha Creacion:',
            'fecha_modificacion' => 'Fecha Modificacion:',
            'sede_fk' => 'Sede:',
            'autorizacion' => 'Autorización:',
            'firma' => 'Firma:',
            'fechaautorizacion' => 'Fecha Autorización:',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSedeFk()
    {
        return $this->hasOne(Sedes::className(), ['sede_pk' => 'sede_fk']);
    }
    
    public function identificacion_existe($attribute, $params)
    {
        //Buscar la identificacion en la tabla
        $table = Habeasdata::find()->where("identificacion=:identificacion", [":identificacion" => $this->identificacion])->andWhere("id!=:id", [':id' => $this->id]);
        //Si la identificacion existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El número de identificación ya existe".$this->id);
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
