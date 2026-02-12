<?php

namespace backend\modules\menu_almuerzo_cena\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\menu_almuerzo_cena\models\MenuAlmuerzoCena;

/**
 * AlmuerzoCenaSearch represents the model behind the search form about `backend\modules\menu_almuerzo_cena\models\MenuAlmuerzoCena`.
 */
class AlmuerzoCenaSearch extends MenuAlmuerzoCena
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nombre', 'tiempo', 'subcategoria', 'imagen', 'estado'], 'safe'],
            [['precio_costo', 'precio_venta'], 'number'],
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
        $query = MenuAlmuerzoCena::find();

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
            'precio_costo' => $this->precio_costo,
            'precio_venta' => $this->precio_venta,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'tiempo', $this->tiempo])
            ->andFilterWhere(['like', 'subcategoria', $this->subcategoria])
            ->andFilterWhere(['like', 'imagen', $this->imagen])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
