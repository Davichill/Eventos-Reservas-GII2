<?php

namespace backend\modules\reservas\models;

use Yii;

/**
 * This is the model class for table "pagos_reservas".
 *
 * @property int $id
 * @property int $id_reserva
 * @property float $monto
 * @property string|null $fecha_pago
 * @property string $metodo_pago
 * @property string|null $referencia
 * @property string $tipo_pago
 * @property string|null $notas
 * @property int|null $registrado_por
 *
 * @property Reservas $reserva
 */
class PagosReservas extends \yii\db\ActiveRecord
{

    /**
     * ENUM field values
     */
    const METODO_PAGO_TRANSFERENCIA = 'Transferencia';
    const METODO_PAGO_TARJETA = 'Tarjeta';
    const METODO_PAGO_EFECTIVO = 'Efectivo';
    const METODO_PAGO_CHEQUE = 'Cheque';
    const TIPO_PAGO_DEPOSITO_1 = 'Deposito 1';
    const TIPO_PAGO_DEPOSITO_2 = 'Deposito 2';
    const TIPO_PAGO_SALDO_FINAL = 'Saldo Final';
    const TIPO_PAGO_ADICIONAL = 'Adicional';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pagos_reservas';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['referencia', 'notas', 'registrado_por'], 'default', 'value' => null],
            [['id_reserva', 'monto', 'metodo_pago', 'tipo_pago'], 'required'],
            [['id_reserva', 'registrado_por'], 'integer'],
            [['monto'], 'number'],
            [['fecha_pago'], 'safe'],
            [['metodo_pago', 'tipo_pago', 'notas'], 'string'],
            [['referencia'], 'string', 'max' => 100],
            ['metodo_pago', 'in', 'range' => array_keys(self::optsMetodoPago())],
            ['tipo_pago', 'in', 'range' => array_keys(self::optsTipoPago())],
            [['id_reserva'], 'exist', 'skipOnError' => true, 'targetClass' => Reservas::class, 'targetAttribute' => ['id_reserva' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_reserva' => 'Id Reserva',
            'monto' => 'Monto',
            'fecha_pago' => 'Fecha Pago',
            'metodo_pago' => 'Metodo Pago',
            'referencia' => 'Referencia',
            'tipo_pago' => 'Tipo Pago',
            'notas' => 'Notas',
            'registrado_por' => 'Registrado Por',
        ];
    }

    /**
     * Gets query for [[Reserva]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReserva()
    {
        return $this->hasOne(Reservas::class, ['id' => 'id_reserva']);
    }


    /**
     * column metodo_pago ENUM value labels
     * @return string[]
     */
    public static function optsMetodoPago()
    {
        return [
            self::METODO_PAGO_TRANSFERENCIA => 'Transferencia',
            self::METODO_PAGO_TARJETA => 'Tarjeta',
            self::METODO_PAGO_EFECTIVO => 'Efectivo',
            self::METODO_PAGO_CHEQUE => 'Cheque',
        ];
    }

    /**
     * column tipo_pago ENUM value labels
     * @return string[]
     */
    public static function optsTipoPago()
    {
        return [
            self::TIPO_PAGO_DEPOSITO_1 => 'Deposito 1',
            self::TIPO_PAGO_DEPOSITO_2 => 'Deposito 2',
            self::TIPO_PAGO_SALDO_FINAL => 'Saldo Final',
            self::TIPO_PAGO_ADICIONAL => 'Adicional',
        ];
    }

    /**
     * @return string
     */
    public function displayMetodoPago()
    {
        return self::optsMetodoPago()[$this->metodo_pago];
    }

    /**
     * @return bool
     */
    public function isMetodoPagoTransferencia()
    {
        return $this->metodo_pago === self::METODO_PAGO_TRANSFERENCIA;
    }

    public function setMetodoPagoToTransferencia()
    {
        $this->metodo_pago = self::METODO_PAGO_TRANSFERENCIA;
    }

    /**
     * @return bool
     */
    public function isMetodoPagoTarjeta()
    {
        return $this->metodo_pago === self::METODO_PAGO_TARJETA;
    }

    public function setMetodoPagoToTarjeta()
    {
        $this->metodo_pago = self::METODO_PAGO_TARJETA;
    }

    /**
     * @return bool
     */
    public function isMetodoPagoEfectivo()
    {
        return $this->metodo_pago === self::METODO_PAGO_EFECTIVO;
    }

    public function setMetodoPagoToEfectivo()
    {
        $this->metodo_pago = self::METODO_PAGO_EFECTIVO;
    }

    /**
     * @return bool
     */
    public function isMetodoPagoCheque()
    {
        return $this->metodo_pago === self::METODO_PAGO_CHEQUE;
    }

    public function setMetodoPagoToCheque()
    {
        $this->metodo_pago = self::METODO_PAGO_CHEQUE;
    }

    /**
     * @return string
     */
    public function displayTipoPago()
    {
        return self::optsTipoPago()[$this->tipo_pago];
    }

    /**
     * @return bool
     */
    public function isTipoPagoDeposito1()
    {
        return $this->tipo_pago === self::TIPO_PAGO_DEPOSITO_1;
    }

    public function setTipoPagoToDeposito1()
    {
        $this->tipo_pago = self::TIPO_PAGO_DEPOSITO_1;
    }

    /**
     * @return bool
     */
    public function isTipoPagoDeposito2()
    {
        return $this->tipo_pago === self::TIPO_PAGO_DEPOSITO_2;
    }

    public function setTipoPagoToDeposito2()
    {
        $this->tipo_pago = self::TIPO_PAGO_DEPOSITO_2;
    }

    /**
     * @return bool
     */
    public function isTipoPagoSaldoFinal()
    {
        return $this->tipo_pago === self::TIPO_PAGO_SALDO_FINAL;
    }

    public function setTipoPagoToSaldoFinal()
    {
        $this->tipo_pago = self::TIPO_PAGO_SALDO_FINAL;
    }

    /**
     * @return bool
     */
    public function isTipoPagoAdicional()
    {
        return $this->tipo_pago === self::TIPO_PAGO_ADICIONAL;
    }

    public function setTipoPagoToAdicional()
    {
        $this->tipo_pago = self::TIPO_PAGO_ADICIONAL;
    }
}
