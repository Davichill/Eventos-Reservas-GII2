<?php

namespace backend\modules\menu_coctel\models;

use Yii;

/**
 * This is the model class for table "menu_coctel".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $categoria
 * @property string $subcategoria
 * @property string|null $imagen
 * @property int|null $estado
 */
class MenuCoctel extends \yii\db\ActiveRecord
{

public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_coctel';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoria', 'imagen'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['nombre', 'subcategoria'], 'required'],
            [['estado'], 'integer'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            // El campo 'imagen' es donde se guarda el binario (BLOB)
            [['imagen'], 'safe'],
            [['categoria', 'subcategoria'], 'string', 'max' => 100],
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
            'subcategoria' => 'Subcategoria',
            'imagen' => 'Imagen',
            'estado' => 'Estado',
        ];
    }

}
