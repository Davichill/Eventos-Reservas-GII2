<?php

namespace backend\modules\mesas\models;

use Yii;

/**
 * This is the model class for table "mesas".
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $imagen_url
 */
class Mesas extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'mesas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imagen_url'], 'default', 'value' => null],
            [['id', 'nombre'], 'required'],
            [['id'], 'integer'],
            [['nombre'], 'string', 'max' => 50],
            [['imagen_url'], 'string', 'max' => 255],
            [['id'], 'unique'],
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
            'imagen_url' => 'Imagen Url',
        ];
    }

}
