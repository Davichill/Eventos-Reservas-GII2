<?php

namespace backend\modules\menu_almuerzo_cena\models;

use Yii;

/**
 * This is the model class for table "menu_almuerzo_cena".
 *
 * @property int $id
 * @property string $nombre
 * @property string $tiempo
 * @property string|null $subcategoria
 * @property string|null $imagen_url
 * @property int|null $estado
 */
class MenuAlmuerzoCena extends \yii\db\ActiveRecord
{

    public $imageFile;
    /**
     * ENUM field values
     */
    const TIEMPO_ENTRADAS = 'Entradas';
    const TIEMPO_PLATO_FUERTE = 'Plato Fuerte';
    const TIEMPO_POSTRES = 'Postres';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_almuerzo_cena';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'tiempo'], 'required'],
            [['tiempo'], 'string'],
            [['estado'], 'integer'],
            [['estado'], 'default', 'value' => 1],
            [['subcategoria'], 'string', 'max' => 100],

            // Regla para el archivo físico (imageFile)
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 1024 * 1024 * 2], // max 2MB

            // El campo 'imagen' recibirá el binario, no lo validamos como string
            [['imagen'], 'safe'],

            ['tiempo', 'in', 'range' => array_keys(self::optsTiempo())],
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
            'tiempo' => 'Tiempo',
            'subcategoria' => 'Subcategoria',
            'imageFile' => 'Imagen del Plato',
            'estado' => 'Estado',
        ];
    }


    public function getImagenEnBase64()
    {
        if ($this->imagen) {
            // Convertimos el binario de la DB a base64 para el tag <img>
            $base64 = base64_encode($this->imagen);
            return "data:image/jpeg;base64," . $base64;
        }
        return null;
    }
    /**
     * column tiempo ENUM value labels
     * @return string[]
     */
    public static function optsTiempo()
    {
        return [
            self::TIEMPO_ENTRADAS => 'Entradas',
            self::TIEMPO_PLATO_FUERTE => 'Plato Fuerte',
            self::TIEMPO_POSTRES => 'Postres',
        ];
    }

    /**
     * @return string
     */
    public function displayTiempo()
    {
        return self::optsTiempo()[$this->tiempo];
    }

    /**
     * @return bool
     */
    public function isTiempoEntradas()
    {
        return $this->tiempo === self::TIEMPO_ENTRADAS;
    }

    public function setTiempoToEntradas()
    {
        $this->tiempo = self::TIEMPO_ENTRADAS;
    }

    /**
     * @return bool
     */
    public function isTiempoPlatoFuerte()
    {
        return $this->tiempo === self::TIEMPO_PLATO_FUERTE;
    }

    public function setTiempoToPlatoFuerte()
    {
        $this->tiempo = self::TIEMPO_PLATO_FUERTE;
    }

    /**
     * @return bool
     */
    public function isTiempoPostres()
    {
        return $this->tiempo === self::TIEMPO_POSTRES;
    }

    public function setTiempoToPostres()
    {
        $this->tiempo = self::TIEMPO_POSTRES;
    }
}
