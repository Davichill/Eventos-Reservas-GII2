<?php

namespace backend\modules\menu_desayuno\models;

use Yii;

/**
 * This is the model class for table "menu_desayunos".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $categoria
 * @property string|null $descripcion
 * @property string|null $imagen_url
 * @property int|null $estado
 */
class MenuDesayunos extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_desayunos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['categoria', 'descripcion', 'imagen_url'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['nombre'], 'required'],
            [['descripcion'], 'string'],
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
            'descripcion' => 'Descripcion',
            'imagen_url' => 'Imagen Url',
            'estado' => 'Estado',
        ];
    }

}
