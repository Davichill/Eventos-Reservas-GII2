<?php

namespace backend\modules\menu_coffee_break\models;

use Yii;

/**
 * This is the model class for table "menu_coffee_break".
 *
 * @property int $id
 * @property string $nombre
 * @property string $categoria
 * @property string|null $imagen_url
 * @property int|null $estado
 */
class MenuCoffeeBreak extends \yii\db\ActiveRecord
{

    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_coffee_break';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imagen'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['nombre', 'categoria'], 'required'],
            [['estado'], 'integer'],
           // Regla para la variable temporal del formulario
        [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        
        // El campo 'imagen' (BLOB) de la base de datos
        [['imagen'], 'safe'],
            [['categoria'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'categoria' => 'Categoria',
            'imagen' => 'Imagen',
            'estado' => 'Estado',
        ];
    }

}
