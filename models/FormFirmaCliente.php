<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Cliente;
use yii\web\UploadedFile;


class FormFirmaCliente extends Model
{
    public $imageFile;
    public $consecutivo;

    public function rules()
    {
        return [
            ['imageFile', 'required', 'message' => 'Campo requerido'],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png'],
            ['consecutivo', 'default'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'imageFile' => 'Archivo de Imagen:',
            'consecutivo' => '',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('firmaCliente/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            return true;
        } else {
            return false;
        }
    }
}
