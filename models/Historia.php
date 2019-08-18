<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "historia".
 *
 * @property int $id_historia
 * @property string $nombre
 * @property int $identificacion
 * @property string $fecha_nacimiento
 * @property string $email
 * @property string $ocupacion
 * @property string $sexo
 * @property int $edad
 * @property string $telefono
 * @property string $celular
 * @property string $direccion
 * @property string $estado_civil
 * @property string $lugar_residencia
 * @property string $aseguradora_salud
 * @property string $fecha_creacion
 * @property string $fecha_modificacion
 * @property int $consumo_grasa 1: alto, 2: normal, 3: bajo
 * @property int $consumo_azucar 1: alto, 2: normal, 3: bajo
 * @property int $fuma 1: si, 0: no
 * @property string $cuanto_diario
 * @property int $alcohol 1: si, 0: no
 * @property int $consumo_alcohol 1: diario, 2: semanal ,3: ocasional
 * @property int $duerme_bien 1: si, 0: no
 * @property int $hace_ejercicio 1: si, 0: no
 * @property string $clase
 * @property string $frecuencia
 * @property string $observaciones
 * @property string $motivo_consulta
 * @property string $enfermedad_actual
 * @property string $revision_por_sistema
 * @property int $aph_patologia
 * @property string $aph_patologia_t
 * @property int $aph_quirurgicos
 * @property string $aph_quirurgicos_t
 * @property int $aph_alergicos
 * @property string $aph_alergicos_t
 * @property int $aph_toxicos
 * @property string $aph_toxicos_t
 * @property int $apm_agregar
 * @property string $apm_agregar_t
 * @property int $apm_menarca
 * @property string $apm_fecha_ultima_m
 * @property int $apm_embarazo
 * @property string $apm_metodo_planificar
 * @property string $examen_fisico
 * @property string $presion_arterial
 * @property string $frecuencia_cardiaca
 * @property string $frecuencia_respiratoria
 * @property string $peso
 * @property string $talla
 * @property string $imc
 * @property string $cabeza
 * @property string $cuello
 * @property string $cardio_respiratorio
 * @property string $abdomen
 * @property string $extremidades
 * @property string $piel
 * @property string $af_observaciones
 * @property string $notas_clinicas
 * @property int $sede_fk
 */
class Historia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'historia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identificacion', 'edad', 'consumo_grasa', 'consumo_azucar', 'fuma', 'alcohol', 'consumo_alcohol', 'duerme_bien', 'hace_ejercicio', 'aph_patologia', 'aph_quirurgicos', 'aph_alergicos', 'aph_toxicos', 'apm_agregar', 'apm_menarca', 'apm_embarazo', 'sede_fk'], 'string'],
            [['fecha_nacimiento'], 'safe'],
            [['telefono', 'celular'], 'required'],
            ['id_historia','default'],
            ['identificacion', 'identificacion_existe'],            
            ['identificacion', 'identificacion_no_existe'],
            [['observaciones', 'motivo_consulta', 'enfermedad_actual', 'revision_por_sistema', 'examen_fisico', 'af_observaciones', 'notas_clinicas'], 'string'],
            [['nombre', 'direccion'], 'string', 'max' => 150],
            [['email', 'estado_civil', 'lugar_residencia', 'aseguradora_salud'], 'string', 'max' => 80],
            [['ocupacion'], 'string', 'max' => 70],
            [['sexo', 'telefono', 'celular', 'presion_arterial', 'frecuencia_cardiaca', 'frecuencia_respiratoria', 'peso', 'talla', 'imc', 'cabeza', 'cuello', 'cardio_respiratorio', 'abdomen', 'extremidades', 'piel'], 'string', 'max' => 20],
            [['cuanto_diario', 'clase', 'frecuencia', 'apm_fecha_ultima_m'], 'string', 'max' => 50],
            [['aph_patologia_t', 'aph_quirurgicos_t', 'aph_alergicos_t', 'aph_toxicos_t', 'apm_agregar_t'], 'string', 'max' => 200],
            [['apm_metodo_planificar'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_historia' => '',
            'nombre' => 'Nombre Completo:',
            'identificacion' => 'Identificación:',
            'fecha_nacimiento' => 'Fecha Nacimiento:',
            'email' => 'Email:',
            'ocupacion' => 'Ocupación:',
            'sexo' => 'Sexo:',
            'edad' => 'Edad:',
            'telefono' => 'Teléfono:',
            'celular' => 'Celular:',
            'direccion' => 'Dirección:',
            'estado_civil' => 'Estado Civil:',
            'lugar_residencia' => 'Lugar Residencia:',
            'aseguradora_salud' => 'Aseguradora de Salud:',            
            'consumo_grasa' => 'Consumo de Grasa:',
            'consumo_azucar' => 'Consumo de Azucar:',
            'fuma' => 'Fuma:',
            'cuanto_diario' => 'Cuanto Diario:',
            'alcohol' => 'Alcohol:',
            'consumo_alcohol' => 'Consumo Alcohol:',
            'duerme_bien' => 'Duerme Bien:',
            'hace_ejercicio' => 'Hace Ejercicio:',
            'clase' => 'Clase:',
            'frecuencia' => 'Frecuencia:',
            'observaciones' => 'Observaciones:',
            'motivo_consulta' => 'Motivo de Consulta:',
            'enfermedad_actual' => 'Enfermedad Actual:',
            'revision_por_sistema' => 'Revision Por Sistema:',
            'aph_patologia' => 'Patologia:',
            'aph_patologia_t' => 'Patologia:',
            'aph_quirurgicos' => 'Quirurgicos:',
            'aph_quirurgicos_t' => 'Quirurgicos:',
            'aph_alergicos' => 'Alergicos:',
            'aph_alergicos_t' => 'Alergicos:',
            'aph_toxicos' => 'Toxicos:',
            'aph_toxicos_t' => 'Toxicos:',
            'apm_agregar' => 'Agregar Antecedentes Obstreticos:',
            'apm_agregar_t' => 'Agregar Antecedentes Obstreticos:',
            'apm_menarca' => 'Menarca:',
            'apm_fecha_ultima_m' => 'Fecha Ultima M:',
            'apm_embarazo' => 'Embarazo:',
            'apm_metodo_planificar' => 'Metodo Planificar:',
            'examen_fisico' => 'Examen Fisico:',
            'presion_arterial' => 'Presion Arterial:',
            'frecuencia_cardiaca' => 'Frecuencia Cardiaca:',
            'frecuencia_respiratoria' => 'Frecuencia Respiratoria:',
            'peso' => 'Peso:',
            'talla' => 'Talla:',
            'imc' => 'Imc:',
            'cabeza' => 'Cabeza:',
            'cuello' => 'Cuello:',
            'cardio_respiratorio' => 'Cardio Respiratorio:',
            'abdomen' => 'Abdomen:',
            'extremidades' => 'Extremidades:',
            'piel' => 'Piel:',
            'af_observaciones' => 'Observaciones:',            
            'sede_fk' => 'Sede:',
        ];
    }
    
    public function identificacion_existe($attribute, $params)
    {
        //Buscar la identificacion en la tabla
        $table = Historia::find()->where("identificacion=:identificacion", [":identificacion" => $this->identificacion])->andWhere("id_historia!=:id_historia", [':id_historia' => $this->id_historia]);       
        //Si la identificacion existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "El número de identificación ya existe".$this->identificacion);
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
