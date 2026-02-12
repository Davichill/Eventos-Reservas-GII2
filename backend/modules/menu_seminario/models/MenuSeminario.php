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
 * @property resource|null $imagen  // Cambiado de imagen_url a imagen
 * @property int|null $estado
 */
class MenuSeminario extends \yii\db\ActiveRecord
{
    public $imageFile;

    /**
     * ENUM field values
     */
    const SECCION_ENTRADA = 'ENTRADA';
    const SECCION_PLATO_FUERTE = 'PLATO FUERTE';
    const SECCION_POSTRE = 'POSTRE';

    public static function tableName()
    {
        return 'menu_seminario';
    }

    public function rules()
    {
        return [
            // HE ELIMINADO la línea de 'imagen_url' que causaba el error
            [['estado'], 'default', 'value' => 1],
            [['nombre', 'seccion', 'categoria'], 'required'],
            [['seccion'], 'string'],
            [['estado'], 'integer'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            
            // Usamos 'imagen' que es tu columna real
            [['imagen'], 'safe'],
            [['categoria'], 'string', 'max' => 100],
            ['seccion', 'in', 'range' => array_keys(self::optsSeccion())],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'seccion' => 'Sección',
            'categoria' => 'Categoría',
            'imagen' => 'Foto del Seminario',
            'imageFile' => 'Archivo de Imagen',
            'estado' => 'Estado',
        ];
    }

    /**
     * Opciones para el dropdown de Sección
     */
    public static function optsSeccion()
    {
        return [
            self::SECCION_ENTRADA => 'ENTRADA',
            self::SECCION_PLATO_FUERTE => 'PLATO FUERTE',
            self::SECCION_POSTRE => 'POSTRE',
        ];
    }

    public function displaySeccion()
    {
        return self::optsSeccion()[$this->seccion] ?? $this->seccion;
    }

    // Métodos de utilidad para la lógica de negocio
    public function isSeccionEntrada() { return $this->seccion === self::SECCION_ENTRADA; }
    public function isSeccionPlatoFuerte() { return $this->seccion === self::SECCION_PLATO_FUERTE; }
    public function isSeccionPostre() { return $this->seccion === self::SECCION_POSTRE; }
}