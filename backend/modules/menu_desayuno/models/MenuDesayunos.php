<?php

namespace backend\modules\menu_desayuno\models;

use Yii;

/**
 * Clase de modelo para la tabla "menu_desayunos".
 */
class MenuDesayunos extends \yii\db\ActiveRecord
{
    // Esta variable recibe el archivo del formulario
    public $imageFile;

    public static function tableName()
    {
        return 'menu_desayunos';
    }

    public function rules()
    {
        return [
            [['nombre'], 'required'],
            [['descripcion'], 'string'],
            [['estado'], 'integer'],
            [['estado'], 'default', 'value' => 1],
            [['categoria'], 'string', 'max' => 100],
            // Regla para el archivo subido
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            // Atributo binario de la base de datos
            [['imagen'], 'safe'], 
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'categoria' => 'Categoría',
            'descripcion' => 'Descripción',
            'imageFile' => 'Foto del Desayuno', // Etiqueta amigable
            'estado' => 'Estado',
        ];
    }
}