<?php

namespace backend\modules\empresas\models;

use Yii;

/**
 * This is the model class for table "empresas".
 *
 * @property int $id
 * @property string $razon_social
 * @property string|null $ruc
 * @property string|null $telefono
 * @property string|null $email
 * @property string|null $direccion
 * @property string|null $estado
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Clientes[] $clientes
 * @property Reservas[] $reservas
 */
class Empresas extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const ESTADO_ACTIVO = 'Activo';
    const ESTADO_INACTIVO = 'Inactivo';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empresas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ruc', 'telefono', 'email', 'direccion'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 'Activo'],
            [['razon_social'], 'required'],
            [['estado'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['razon_social', 'email', 'direccion'], 'string', 'max' => 255],
            [['ruc'], 'string', 'max' => 20],
            [['telefono'], 'string', 'max' => 50],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
            [['ruc'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'razon_social' => 'Razon Social',
            'ruc' => 'Ruc',
            'telefono' => 'Telefono',
            'email' => 'Email',
            'direccion' => 'Direccion',
            'estado' => 'Estado',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Clientes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClientes()
    {
        return $this->hasMany(Clientes::class, ['id_empresa' => 'id']);
    }

    /**
     * Gets query for [[Reservas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReservas()
    {
        return $this->hasMany(Reservas::class, ['id_empresa' => 'id']);
    }


    /**
     * column estado ENUM value labels
     * @return string[]
     */
    public static function optsEstado()
    {
        return [
            self::ESTADO_ACTIVO => 'Activo',
            self::ESTADO_INACTIVO => 'Inactivo',
        ];
    }

    /**
     * @return string
     */
    public function displayEstado()
    {
        return self::optsEstado()[$this->estado];
    }

    /**
     * @return bool
     */
    public function isEstadoActivo()
    {
        return $this->estado === self::ESTADO_ACTIVO;
    }

    public function setEstadoToActivo()
    {
        $this->estado = self::ESTADO_ACTIVO;
    }

    /**
     * @return bool
     */
    public function isEstadoInactivo()
    {
        return $this->estado === self::ESTADO_INACTIVO;
    }

    public function setEstadoToInactivo()
    {
        $this->estado = self::ESTADO_INACTIVO;
    }
}
