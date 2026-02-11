<?php

namespace backend\modules\clientes\models;

use Yii;
use backend\modules\empresas\models\Empresas;


/**
 * This is the model class for table "clientes".
 *
 * @property int $id
 * @property int|null $id_empresa
 * @property string|null $identificacion
 * @property string|null $razon_social
 * @property string|null $representante_legal
 * @property string|null $direccion_fiscal
 * @property string|null $ciudad
 * @property string|null $pais
 * @property string|null $correo_facturacion
 * @property string|null $cliente_nombre
 * @property string|null $cliente_apellido
 * @property string|null $cliente_email
 * @property string|null $cliente_telefono
 * @property string $fecha_registro
 * @property int|null $id_usuario_creador
 * @property string|null $estado
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property Empresas $empresa
 */
class Clientes extends \yii\db\ActiveRecord
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
        return 'clientes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_empresa', 'identificacion', 'razon_social', 'representante_legal', 'direccion_fiscal', 'ciudad', 'pais', 'correo_facturacion', 'cliente_nombre', 'cliente_apellido', 'cliente_email', 'cliente_telefono', 'id_usuario_creador'], 'default', 'value' => null],
            [['estado'], 'default', 'value' => 'Activo'],
            [['id_empresa', 'id_usuario_creador'], 'integer'],
            [['direccion_fiscal', 'estado'], 'string'],
            [['fecha_registro', 'created_at', 'updated_at'], 'safe'],
            [['identificacion', 'cliente_telefono'], 'string', 'max' => 20],
            [['razon_social', 'representante_legal', 'cliente_nombre'], 'string', 'max' => 255],
            [['ciudad', 'pais', 'correo_facturacion', 'cliente_apellido', 'cliente_email'], 'string', 'max' => 100],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
            [['id_empresa'], 'exist', 'skipOnError' => true, 'targetClass' => Empresas::class, 'targetAttribute' => ['id_empresa' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_empresa' => 'Id Empresa',
            'identificacion' => 'Identificacion',
            'razon_social' => 'Razon Social',
            'representante_legal' => 'Representante Legal',
            'direccion_fiscal' => 'Direccion Fiscal',
            'ciudad' => 'Ciudad',
            'pais' => 'Pais',
            'correo_facturacion' => 'Correo Facturacion',
            'cliente_nombre' => 'Cliente Nombre',
            'cliente_apellido' => 'Cliente Apellido',
            'cliente_email' => 'Cliente Email',
            'cliente_telefono' => 'Cliente Telefono',
            'fecha_registro' => 'Fecha Registro',
            'id_usuario_creador' => 'Id Usuario Creador',
            'estado' => 'Estado',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Empresa]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresas::class, ['id' => 'id_empresa']);
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
