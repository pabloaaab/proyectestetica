<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\SeccionTipo;

/**
 * ContactForm is the model behind the contact form.
 */
class FormSeccionTipo extends Model
{
    public $seccion_tipo_pk;
    public $tipo;

    public function rules()
    {
        return [
            ['seccion_tipo_pk', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            [['tipo'], 'required'],
            ['tipo', 'tipo_existe'],
            [['tipo'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'seccion_tipo_pk' => '',            
            'tipo' => 'Tipo:',

        ];
    }

    public function tipo_existe($attribute, $params)
    {
        //Buscar la seccion en la tabla
        $table = SeccionTipo::find()->where("tipo=:tipo", [":tipo" => $this->tipo])->andWhere("seccion_tipo_pk!=:seccion_tipo_pk", [':seccion_tipo_pk' => $this->seccion_tipo_pk]);
        //Si la seccion existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "La seccion ya existe: ".$this->tipo);
        }
    }
}
