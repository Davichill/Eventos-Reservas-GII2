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
            [['subcategoria', 'imagen_url'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['nombre', 'tiempo'], 'required'],
            [['tiempo'], 'string'],
            [['estado'], 'integer'],
            [['nombre', 'imagen_url'], 'string', 'max' => 255],
            [['subcategoria'], 'string', 'max' => 100],
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
            'imagen_url' => 'Imagen Url',
            'estado' => 'Estado',
        ];
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
