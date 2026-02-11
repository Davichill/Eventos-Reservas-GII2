<?php

namespace backend\modules\menu_seminario\models;

use Yii;

/**
 * This is the model class for table "menu_seminario".
 *
 * @property int $id
 * @property string $nombre
 * @property string $seccion
 * @property string $categoria
 * @property string|null $imagen_url
 * @property int|null $estado
 */
class MenuSeminario extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const SECCION_ENTRADA = 'ENTRADA';
    const SECCION_PLATO_FUERTE = 'PLATO FUERTE';
    const SECCION_POSTRE = 'POSTRE';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'menu_seminario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['imagen_url'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 1],
            [['nombre', 'seccion', 'categoria'], 'required'],
            [['seccion'], 'string'],
            [['estado'], 'integer'],
            [['nombre', 'imagen_url'], 'string', 'max' => 255],
            [['categoria'], 'string', 'max' => 100],
            ['seccion', 'in', 'range' => array_keys(self::optsSeccion())],
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
            'seccion' => 'Seccion',
            'categoria' => 'Categoria',
            'imagen_url' => 'Imagen Url',
            'estado' => 'Estado',
        ];
    }


    /**
     * column seccion ENUM value labels
     * @return string[]
     */
    public static function optsSeccion()
    {
        return [
            self::SECCION_ENTRADA => 'ENTRADA',
            self::SECCION_PLATO_FUERTE => 'PLATO FUERTE',
            self::SECCION_POSTRE => 'POSTRE',
        ];
    }

    /**
     * @return string
     */
    public function displaySeccion()
    {
        return self::optsSeccion()[$this->seccion];
    }

    /**
     * @return bool
     */
    public function isSeccionEntrada()
    {
        return $this->seccion === self::SECCION_ENTRADA;
    }

    public function setSeccionToEntrada()
    {
        $this->seccion = self::SECCION_ENTRADA;
    }

    /**
     * @return bool
     */
    public function isSeccionPlatoFuerte()
    {
        return $this->seccion === self::SECCION_PLATO_FUERTE;
    }

    public function setSeccionToPlatoFuerte()
    {
        $this->seccion = self::SECCION_PLATO_FUERTE;
    }

    /**
     * @return bool
     */
    public function isSeccionPostre()
    {
        return $this->seccion === self::SECCION_POSTRE;
    }

    public function setSeccionToPostre()
    {
        $this->seccion = self::SECCION_POSTRE;
    }
}
