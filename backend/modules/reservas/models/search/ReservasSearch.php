<?php

namespace backend\modules\reservas\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\reservas\models\Reservas;

/**
 * ReservasSearch represents the model behind the search form about `backend\modules\reservas\models\Reservas`.
 */
class ReservasSearch extends Reservas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_cliente', 'id_empresa', 'id_tipo_evento', 'cantidad_personas', 'id_mesa', 'id_salon', 'id_coordinador'], 'integer'],
            [['token', 'cliente_nombre', 'firma_nombre', 'firma_identificacion', 'contacto_evento_nombre', 'contacto_evento_telefono', 'nombre_evento', 'fecha_evento', 'hora_evento', 'equipos_audiovisuales', 'estado_pago', 'estado', 'notas', 'fecha_creacion', 'hora_inicio', 'hora_fin', 'manteleria', 'color_servilleta', 'logistica', 'observaciones', 'planimetria_url', 'menu_opcion'], 'safe'],
            [['total_evento', 'total_pagado'], 'number'],
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
        $query = Reservas::find();

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
            'id_cliente' => $this->id_cliente,
            'id_empresa' => $this->id_empresa,
            'id_tipo_evento' => $this->id_tipo_evento,
            'fecha_evento' => $this->fecha_evento,
            'hora_evento' => $this->hora_evento,
            'cantidad_personas' => $this->cantidad_personas,
            'id_mesa' => $this->id_mesa,
            'id_salon' => $this->id_salon,
            'total_evento' => $this->total_evento,
            'total_pagado' => $this->total_pagado,
            'fecha_creacion' => $this->fecha_creacion,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'id_coordinador' => $this->id_coordinador,
        ]);

        $query->andFilterWhere(['like', 'token', $this->token])
            ->andFilterWhere(['like', 'cliente_nombre', $this->cliente_nombre])
            ->andFilterWhere(['like', 'firma_nombre', $this->firma_nombre])
            ->andFilterWhere(['like', 'firma_identificacion', $this->firma_identificacion])
            ->andFilterWhere(['like', 'contacto_evento_nombre', $this->contacto_evento_nombre])
            ->andFilterWhere(['like', 'contacto_evento_telefono', $this->contacto_evento_telefono])
            ->andFilterWhere(['like', 'nombre_evento', $this->nombre_evento])
            ->andFilterWhere(['like', 'equipos_audiovisuales', $this->equipos_audiovisuales])
            ->andFilterWhere(['like', 'estado_pago', $this->estado_pago])
            ->andFilterWhere(['like', 'estado', $this->estado])
            ->andFilterWhere(['like', 'notas', $this->notas])
            ->andFilterWhere(['like', 'manteleria', $this->manteleria])
            ->andFilterWhere(['like', 'color_servilleta', $this->color_servilleta])
            ->andFilterWhere(['like', 'logistica', $this->logistica])
            ->andFilterWhere(['like', 'observaciones', $this->observaciones])
            ->andFilterWhere(['like', 'planimetria_url', $this->planimetria_url])
            ->andFilterWhere(['like', 'menu_opcion', $this->menu_opcion]);

        return $dataProvider;
    }
}
