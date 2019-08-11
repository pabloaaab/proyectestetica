<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "eventos".
 *
 * @property int $id
 * @property string $asunto
 * @property string $fechai
 * @property string $fechat
 * @property string $color
 * @property int $sede_fk
 * @property string $nombres
 * @property int $seccion_tipo_fk
 * @property int $cancelo_no_asistio
 * @property int $id_profesional
 * @property int $maquina
 * @property string $observaciones
 * @property string $telefono
 * @property string $identificacion
 *
 * @property Sedes $sedeFk
 * @property SeccionTipo $seccionTipoFk
 * @property Maquina $maquina0
 * @property Profesionales $profesional
 */
class Eventos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'eventos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
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

    /**
     * {@inheritdoc}
     */
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSedeFk()
    {
        return $this->hasOne(Sedes::className(), ['sede_pk' => 'sede_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSeccionTipoFk()
    {
        return $this->hasOne(SeccionTipo::className(), ['seccion_tipo_pk' => 'seccion_tipo_fk']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaquina0()
    {
        return $this->hasOne(Maquina::className(), ['id_maquina' => 'maquina']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfesional()
    {
        return $this->hasOne(Profesionales::className(), ['id_profesional' => 'id_profesional']);
    }
    
    public function getcancelo()
    {
        if($this->cancelo_no_asistio == 1){
            $cancelo = "SI";
        }else{
            $cancelo = "NO";
        }
        return $cancelo;
    }
}
