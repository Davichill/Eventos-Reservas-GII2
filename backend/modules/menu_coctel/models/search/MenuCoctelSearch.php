<?php

namespace backend\modules\menu_coctel\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\menu_coctel\models\menuCoctel;

/**
 * MenuCoctelSearch represents the model behind the search form about `backend\modules\menu_coctel\models\menuCoctel`.
 */
class MenuCoctelSearch extends menuCoctel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['nombre', 'categoria', 'subcategoria', 'imagen', 'estado'], 'safe'],
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
        $query = menuCoctel::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'estado' => $this->estado, // Movido aquí por ser entero/exacto
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'categoria', $this->categoria])
            ->andFilterWhere(['like', 'subcategoria', $this->subcategoria]);

        // ELIMINADO: ->andFilterWhere(['like', 'imagen', $this->imagen_url])
        // No se filtra por contenido binario.

        return $dataProvider;
    }
}
