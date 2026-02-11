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
            [['imagen_url'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['nombre', 'categoria'], 'required'],
            [['estado'], 'integer'],
            [['nombre', 'imagen_url'], 'string', 'max' => 255],
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
            'imagen_url' => 'Imagen Url',
            'estado' => 'Estado',
        ];
    }

}
