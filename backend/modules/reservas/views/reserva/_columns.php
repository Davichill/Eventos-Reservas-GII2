<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'estado',
        'format' => 'raw',
        'value' => function($model) {
            $class = strtolower($model->estado) == 'confirmada' ? 'badge-success' : 'badge-warning';
            return '<span class="badge ' . $class . '">' . strtoupper($model->estado) . '</span>';
        },
    ],
    [
        'class' => 'kartik\grid\DataColumn',
        'label' => 'Fecha/Hora',
        'format' => 'raw',
        'value' => function($model) {
            // Usamos fecha_evento que es el nombre en tu SQL
            return '<strong>' . date("d/m/Y", strtotime($model->fecha_evento)) . '</strong><br>' .
                   '<small>🕒 ' . $model->hora_inicio . ' a ' . $model->hora_fin . '</small>';
        },
    ],
    [
        'class' => 'kartik\grid\DataColumn',
        'label' => 'Facturación / Cliente',
        'format' => 'raw',
        'value' => function($model) {
            // Usamos la relación que definimos en el punto 1
            $cliente = $model->cliente; 
            $nombre = $cliente ? ($cliente->razon_social ?: $cliente->cliente_nombre) : 'N/A';
            $id = $cliente ? $cliente->identificacion : '---';
            
            return '<div style="font-weight: bold; color: #27ae60;">' . Html::encode($nombre) . '</div>' .
                   '<small>ID: ' . Html::encode($id) . '</small>';
        },
    ],
    [
        'class' => 'kartik\grid\DataColumn',
        'label' => 'Evento/Pax',
        'format' => 'raw',
        'value' => function($model) {
            // Usamos getTipoEvento
            $tipo = $model->tipoEvento ? $model->tipoEvento->nombre : 'Sin tipo';
            return '<strong>' . Html::encode($tipo) . '</strong><br>' .
                   '<small>👥 ' . $model->cantidad_personas . ' Pax</small>';
        },
    ],
    [
        'class' => 'kartik\grid\DataColumn',
        'label' => 'Detalles Críticos',
        'format' => 'raw',
        'value' => function($model) {
            $html = '';
            if ($model->observaciones) {
                $html .= '<div class="badge-cocina" style="color:orange">⚠️ Cocina: ' . substr(Html::encode($model->observaciones), 0, 30) . '...</div>';
            }
            if ($model->equipos_audiovisuales) {
                $html .= '<div class="badge-it" style="color:blue">🎤 IT: ' . substr(Html::encode($model->equipos_audiovisuales), 0, 30) . '...</div>';
            }
            return $html ?: '<em class="text-muted">Sin detalles</em>';
        },
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'urlCreator' => function($action, $model, $key, $index) {
                return Url::to([$action, 'id' => $key]);
        },
    ],
];