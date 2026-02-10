<?php

namespace backend\modules\clientes\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\clientes\models\Clientes;

/**
 * ClienteSearch represents the model behind the search form about `backend\modules\clientes\models\Clientes`.
 */
class ClienteSearch extends Clientes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_empresa', 'id_usuario_creador'], 'integer'],
            [['identificacion', 'razon_social', 'representante_legal', 'direccion_fiscal', 'ciudad', 'pais', 'correo_facturacion', 'cliente_nombre', 'cliente_apellido', 'cliente_email', 'cliente_telefono', 'fecha_registro', 'estado', 'created_at', 'updated_at'], 'safe'],
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
        $query = Clientes::find();

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
            'id_empresa' => $this->id_empresa,
            'fecha_registro' => $this->fecha_registro,
            'id_usuario_creador' => $this->id_usuario_creador,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'identificacion', $this->identificacion])
            ->andFilterWhere(['like', 'razon_social', $this->razon_social])
            ->andFilterWhere(['like', 'representante_legal', $this->representante_legal])
            ->andFilterWhere(['like', 'direccion_fiscal', $this->direccion_fiscal])
            ->andFilterWhere(['like', 'ciudad', $this->ciudad])
            ->andFilterWhere(['like', 'pais', $this->pais])
            ->andFilterWhere(['like', 'correo_facturacion', $this->correo_facturacion])
            ->andFilterWhere(['like', 'cliente_nombre', $this->cliente_nombre])
            ->andFilterWhere(['like', 'cliente_apellido', $this->cliente_apellido])
            ->andFilterWhere(['like', 'cliente_email', $this->cliente_email])
            ->andFilterWhere(['like', 'cliente_telefono', $this->cliente_telefono])
            ->andFilterWhere(['like', 'estado', $this->estado]);

        return $dataProvider;
    }
}
