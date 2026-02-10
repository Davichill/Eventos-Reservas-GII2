<?php

namespace backend\modules\reservas\models;

use Yii;

/**
 * This is the model class for table "salones".
 *
 * @property int $id
 * @property string $nombre_salon
 * @property int|null $capacidad
 * @property int|null $subdivision_de ID del salón padre si es una subdivisión
 *
 * @property Reservas[] $reservas
 * @property Salones[] $salones
 * @property Salones $subdivisionDe
 */
class Salones extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['capacidad', 'subdivision_de'], 'default', 'value' => null],
            [['nombre_salon'], 'required'],
            [['capacidad', 'subdivision_de'], 'integer'],
            [['nombre_salon'], 'string', 'max' => 100],
            [['subdivision_de'], 'exist', 'skipOnError' => true, 'targetClass' => Salones::class, 'targetAttribute' => ['subdivision_de' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_salon' => 'Nombre Salon',
            'capacidad' => 'Capacidad',
            'subdivision_de' => 'ID del salón padre si es una subdivisión',
        ];
    }

    /**
     * Gets query for [[Reservas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReservas()
    {
        return $this->hasMany(Reservas::class, ['id_salon' => 'id']);
    }

    /**
     * Gets query for [[Salones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSalones()
    {
        return $this->hasMany(Salones::class, ['subdivision_de' => 'id']);
    }

    /**
     * Gets query for [[SubdivisionDe]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubdivisionDe()
    {
        return $this->hasOne(Salones::class, ['id' => 'subdivision_de']);
    }

}
