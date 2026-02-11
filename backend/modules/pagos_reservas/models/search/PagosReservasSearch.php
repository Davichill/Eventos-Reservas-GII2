<?php

namespace backend\modules\pagos_reservas\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\pagos_reservas\models\PagosReservas;

/**
 * PagosReservasSearch represents the model behind the search form about `backend\modules\pagos_reservas\models\PagosReservas`.
 */
class PagosReservasSearch extends PagosReservas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_reserva', 'registrado_por'], 'integer'],
            [['monto'], 'number'],
            [['fecha_pago', 'metodo_pago', 'referencia', 'tipo_pago', 'notas'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PagosReservas::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'id_reserva' => $this->id_reserva,
            'monto' => $this->monto,
            'fecha_pago' => $this->fecha_pago,
            'registrado_por' => $this->registrado_por,
        ]);

        $query->andFilterWhere(['like', 'metodo_pago', $this->metodo_pago])
            ->andFilterWhere(['like', 'referencia', $this->referencia])
            ->andFilterWhere(['like', 'tipo_pago', $this->tipo_pago])
            ->andFilterWhere(['like', 'notas', $this->notas]);

        return $dataProvider;
    }
}
