<?php

namespace backend\modules\reservas\models;

use Yii;

/**
 * This is the model class for table "reserva_detalles_menu".
 *
 * @property int $id
 * @property int|null $id_reserva
 * @property string|null $categoria
 * @property string|null $nombre_plato
 */
class ReservaDetallesMenu extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reserva_detalles_menu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_reserva', 'categoria', 'nombre_plato'], 'default', 'value' => null],
            [['id_reserva'], 'integer'],
            [['categoria'], 'string', 'max' => 50],
            [['nombre_plato'], 'string', 'max' => 255],
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
            'categoria' => 'Categoria',
            'nombre_plato' => 'Nombre Plato',
        ];
    }

}
