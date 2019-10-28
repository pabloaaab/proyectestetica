<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Sedes;

/**
 * ContactForm is the model behind the contact form.
 */
class FormSede extends Model
{
    public $sede_pk;
    public $sede;

    public function rules()
    {
        return [

            ['sede_pk', 'match', 'pattern' => '/^[0-9\s]+$/i', 'message' => 'Sólo se aceptan números'],
            ['sede', 'sede_existe'],
            ['sede', 'required', 'message' => 'Campo requerido'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'sede_pk' => '',
            'sede' => 'Sede:',

        ];
    }

    public function sede_existe($attribute, $params)
    {
        //Buscar la sede en la tabla
        $table = Sedes::find()->where("sede=:sede", [":sede" => $this->sede])->andWhere("sede_pk!=:sede_pk", [':sede_pk' => $this->sede_pk]);
        //Si la sede existe mostrar el error
        if ($table->count() == 1)
        {
            $this->addError($attribute, "La Sede ya existe ".$this->sede);
        }
    }
}
