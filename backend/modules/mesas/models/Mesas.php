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

    public $imageFile;
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
            
            [['id', 'nombre'], 'required'],
            [['id'], 'integer'],
            [['nombre'], 'string', 'max' => 50],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],

            // Usamos 'imagen' que es tu columna real
            [['imagen'], 'safe'],
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
            'imagen' => 'Foto del Seminario',
        ];
    }

}
