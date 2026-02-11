<?php

namespace backend\modules\reservas\models;

use Yii;

/**
 * This is the model class for table "admin_usuarios".
 *
 * @property int $id
 * @property string $usuario
 * @property string $password
 * @property string|null $nombre_completo
 * @property string|null $tipo
 * @property string|null $activo
 *
 * @property Reservas[] $reservas
 */
class AdminUsuarios extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const TIPO_NORMAL = 'normal';
    const TIPO_PRINCIPAL = 'principal';
    const ACTIVO_1 = '1';
    const ACTIVO_0 = '0';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_completo'], 'default', 'value' => null],
            [['tipo'], 'default', 'value' => 'normal'],
            [['activo'], 'default', 'value' => 1],
            [['usuario', 'password'], 'required'],
            [['tipo', 'activo'], 'string'],
            [['usuario'], 'string', 'max' => 50],
            [['password'], 'string', 'max' => 255],
            [['nombre_completo'], 'string', 'max' => 100],
            ['tipo', 'in', 'range' => array_keys(self::optsTipo())],
            ['activo', 'in', 'range' => array_keys(self::optsActivo())],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario' => 'Usuario',
            'password' => 'Password',
            'nombre_completo' => 'Nombre Completo',
            'tipo' => 'Tipo',
            'activo' => 'Activo',
        ];
    }

    /**
     * Gets query for [[Reservas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReservas()
    {
        return $this->hasMany(Reservas::class, ['id_coordinador' => 'id']);
    }


    /**
     * column tipo ENUM value labels
     * @return string[]
     */
    public static function optsTipo()
    {
        return [
            self::TIPO_NORMAL => 'normal',
            self::TIPO_PRINCIPAL => 'principal',
        ];
    }

    /**
     * column activo ENUM value labels
     * @return string[]
     */
    public static function optsActivo()
    {
        return [
            self::ACTIVO_1 => '1',
            self::ACTIVO_0 => '0',
        ];
    }

    /**
     * @return string
     */
    public function displayTipo()
    {
        return self::optsTipo()[$this->tipo];
    }

    /**
     * @return bool
     */
    public function isTipoNormal()
    {
        return $this->tipo === self::TIPO_NORMAL;
    }

    public function setTipoToNormal()
    {
        $this->tipo = self::TIPO_NORMAL;
    }

    /**
     * @return bool
     */
    public function isTipoPrincipal()
    {
        return $this->tipo === self::TIPO_PRINCIPAL;
    }

    public function setTipoToPrincipal()
    {
        $this->tipo = self::TIPO_PRINCIPAL;
    }

    /**
     * @return string
     */
    public function displayActivo()
    {
        return self::optsActivo()[$this->activo];
    }

    /**
     * @return bool
     */
    public function isActivo1()
    {
        return $this->activo === self::ACTIVO_1;
    }

    public function setActivoTo1()
    {
        $this->activo = self::ACTIVO_1;
    }

    /**
     * @return bool
     */
    public function isActivo0()
    {
        return $this->activo === self::ACTIVO_0;
    }

    public function setActivoTo0()
    {
        $this->activo = self::ACTIVO_0;
    }
}
