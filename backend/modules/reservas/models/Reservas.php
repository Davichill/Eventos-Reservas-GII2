<?php

namespace backend\modules\reservas\models;

use Yii;

/**
 * This is the model class for table "reservas".
 *
 * @property int $id
 * @property string|null $token
 * @property int|null $id_cliente
 * @property int|null $id_empresa
 * @property string $cliente_nombre
 * @property string|null $firma_nombre
 * @property string|null $firma_identificacion
 * @property string|null $contacto_evento_nombre
 * @property string|null $contacto_evento_telefono
 * @property int $id_tipo_evento
 * @property string|null $nombre_evento
 * @property string $fecha_evento
 * @property string $hora_evento
 * @property int $cantidad_personas
 * @property string|null $equipos_audiovisuales
 * @property int|null $id_mesa
 * @property int|null $id_salon
 * @property float|null $total_evento
 * @property float|null $total_pagado
 * @property string|null $estado_pago
 * @property string|null $estado
 * @property string|null $notas
 * @property string $fecha_creacion
 * @property string|null $hora_inicio
 * @property string|null $hora_fin
 * @property string|null $manteleria
 * @property string|null $color_servilleta
 * @property string|null $logistica
 * @property string|null $observaciones
 * @property string|null $planimetria_url
 * @property string|null $menu_opcion
 * @property int|null $id_coordinador
 *
 * @property AdminUsuarios $coordinador
 * @property Empresas $empresa
 * @property PagosReservas[] $pagosReservas
 * @property Salones $salon
 */
class Reservas extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const ESTADO_PAGO_PENDIENTE = 'Pendiente';
    const ESTADO_PAGO_PARCIAL = 'Parcial';
    const ESTADO_PAGO_PAGADO = 'Pagado';
    const ESTADO_PENDIENTE = 'Pendiente';
    const ESTADO_CONFIRMADA = 'Confirmada';
    const ESTADO_CANCELADA = 'Cancelada';
    const ESTADO_TENTATIVA = 'Tentativa';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reservas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token', 'id_cliente', 'id_empresa', 'firma_nombre', 'firma_identificacion', 'contacto_evento_nombre', 'contacto_evento_telefono', 'nombre_evento', 'equipos_audiovisuales', 'id_mesa', 'id_salon', 'notas', 'hora_inicio', 'hora_fin', 'manteleria', 'color_servilleta', 'logistica', 'observaciones', 'planimetria_url', 'menu_opcion', 'id_coordinador'], 'default', 'value' => null],
            [['total_pagado'], 'default', 'value' => 0.00],
            [['estado'], 'default', 'value' => 'Pendiente'],
            [['id_cliente', 'id_empresa', 'id_tipo_evento', 'cantidad_personas', 'id_mesa', 'id_salon', 'id_coordinador'], 'integer'],
            [['cliente_nombre', 'id_tipo_evento', 'fecha_evento', 'hora_evento', 'cantidad_personas'], 'required'],
            [['fecha_evento', 'hora_evento', 'fecha_creacion', 'hora_inicio', 'hora_fin'], 'safe'],
            [['equipos_audiovisuales', 'estado_pago', 'estado', 'notas', 'logistica', 'observaciones', 'menu_opcion'], 'string'],
            [['total_evento', 'total_pagado'], 'number'],
            [['token', 'cliente_nombre', 'manteleria'], 'string', 'max' => 100],
            [['firma_nombre', 'contacto_evento_nombre', 'nombre_evento', 'planimetria_url'], 'string', 'max' => 255],
            [['firma_identificacion', 'contacto_evento_telefono'], 'string', 'max' => 20],
            [['color_servilleta'], 'string', 'max' => 50],
            ['estado_pago', 'in', 'range' => array_keys(self::optsEstadoPago())],
            ['estado', 'in', 'range' => array_keys(self::optsEstado())],
            [['token'], 'unique'],
            [['id_salon'], 'exist', 'skipOnError' => true, 'targetClass' => Salones::class, 'targetAttribute' => ['id_salon' => 'id']],
            [['id_empresa'], 'exist', 'skipOnError' => true, 'targetClass' => Empresas::class, 'targetAttribute' => ['id_empresa' => 'id']],
            [['id_coordinador'], 'exist', 'skipOnError' => true, 'targetClass' => AdminUsuarios::class, 'targetAttribute' => ['id_coordinador' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'id_cliente' => 'Id Cliente',
            'id_empresa' => 'Id Empresa',
            'cliente_nombre' => 'Cliente Nombre',
            'firma_nombre' => 'Firma Nombre',
            'firma_identificacion' => 'Firma Identificacion',
            'contacto_evento_nombre' => 'Contacto Evento Nombre',
            'contacto_evento_telefono' => 'Contacto Evento Telefono',
            'id_tipo_evento' => 'Id Tipo Evento',
            'nombre_evento' => 'Nombre Evento',
            'fecha_evento' => 'Fecha Evento',
            'hora_evento' => 'Hora Evento',
            'cantidad_personas' => 'Cantidad Personas',
            'equipos_audiovisuales' => 'Equipos Audiovisuales',
            'id_mesa' => 'Id Mesa',
            'id_salon' => 'Id Salon',
            'total_evento' => 'Total Evento',
            'total_pagado' => 'Total Pagado',
            'estado_pago' => 'Estado Pago',
            'estado' => 'Estado',
            'notas' => 'Notas',
            'fecha_creacion' => 'Fecha Creacion',
            'hora_inicio' => 'Hora Inicio',
            'hora_fin' => 'Hora Fin',
            'manteleria' => 'Manteleria',
            'color_servilleta' => 'Color Servilleta',
            'logistica' => 'Logistica',
            'observaciones' => 'Observaciones',
            'planimetria_url' => 'Planimetria Url',
            'menu_opcion' => 'Menu Opcion',
            'id_coordinador' => 'Id Coordinador',
        ];
    }

    /**
     * Gets query for [[Coordinador]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCoordinador()
    {
        return $this->hasOne(AdminUsuarios::class, ['id' => 'id_coordinador']);
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
     * Gets query for [[PagosReservas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPagosReservas()
    {
        return $this->hasMany(PagosReservas::class, ['id_reserva' => 'id']);
    }

    /**
     * Gets query for [[Salon]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSalon()
    {
        return $this->hasOne(Salones::class, ['id' => 'id_salon']);
    }


    /**
     * column estado_pago ENUM value labels
     * @return string[]
     */
    public static function optsEstadoPago()
    {
        return [
            self::ESTADO_PAGO_PENDIENTE => 'Pendiente',
            self::ESTADO_PAGO_PARCIAL => 'Parcial',
            self::ESTADO_PAGO_PAGADO => 'Pagado',
        ];
    }

    /**
     * column estado ENUM value labels
     * @return string[]
     */
    public static function optsEstado()
    {
        return [
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_CONFIRMADA => 'Confirmada',
            self::ESTADO_CANCELADA => 'Cancelada',
            self::ESTADO_TENTATIVA => 'Tentativa',
        ];
    }

    /**
     * @return string
     */
    public function displayEstadoPago()
    {
        return self::optsEstadoPago()[$this->estado_pago];
    }

    /**
     * @return bool
     */
    public function isEstadoPagoPendiente()
    {
        return $this->estado_pago === self::ESTADO_PAGO_PENDIENTE;
    }

    public function setEstadoPagoToPendiente()
    {
        $this->estado_pago = self::ESTADO_PAGO_PENDIENTE;
    }

    /**
     * @return bool
     */
    public function isEstadoPagoParcial()
    {
        return $this->estado_pago === self::ESTADO_PAGO_PARCIAL;
    }

    public function setEstadoPagoToParcial()
    {
        $this->estado_pago = self::ESTADO_PAGO_PARCIAL;
    }

    /**
     * @return bool
     */
    public function isEstadoPagoPagado()
    {
        return $this->estado_pago === self::ESTADO_PAGO_PAGADO;
    }

    public function setEstadoPagoToPagado()
    {
        $this->estado_pago = self::ESTADO_PAGO_PAGADO;
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
    public function isEstadoPendiente()
    {
        return $this->estado === self::ESTADO_PENDIENTE;
    }

    public function setEstadoToPendiente()
    {
        $this->estado = self::ESTADO_PENDIENTE;
    }

    /**
     * @return bool
     */
    public function isEstadoConfirmada()
    {
        return $this->estado === self::ESTADO_CONFIRMADA;
    }

    public function setEstadoToConfirmada()
    {
        $this->estado = self::ESTADO_CONFIRMADA;
    }

    /**
     * @return bool
     */
    public function isEstadoCancelada()
    {
        return $this->estado === self::ESTADO_CANCELADA;
    }

    public function setEstadoToCancelada()
    {
        $this->estado = self::ESTADO_CANCELADA;
    }

    /**
     * @return bool
     */
    public function isEstadoTentativa()
    {
        return $this->estado === self::ESTADO_TENTATIVA;
    }

    public function setEstadoToTentativa()
    {
        $this->estado = self::ESTADO_TENTATIVA;
    }

    /**
     * Relación con el Cliente
     */
    public function getCliente()
    {
        // Nota: Asegúrate de que la clase Clientes exista en ese namespace
        return $this->hasOne(\backend\modules\clientes\models\Clientes::class, ['id' => 'id_cliente']);
    }

    /**
     * Relación con el Tipo de Evento
     */
    public function getTipoEvento()
    {
        // Suponiendo que la tabla se llama tipos_evento y el modelo TiposEvento
        return $this->hasOne(\backend\modules\reservas\models\TiposEvento::class, ['id' => 'id_tipo_evento']);
    }
}
