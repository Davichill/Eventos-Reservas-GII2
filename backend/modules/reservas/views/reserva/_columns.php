<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'estado', // FILTRO ACTIVO
        'format' => 'raw',
        'hAlign' => 'center',
        'width' => '150px',
        'filter' => [
            'CONFIRMADA' => 'CONFIRMADA', 
            'PENDIENTE' => 'PENDIENTE', 
            'CANCELADO' => 'CANCELADO'
        ],
        'value' => function($model) {
            $estado = strtoupper($model->estado);
            $class = 'badge-secondary';
            if ($estado == 'CONFIRMADA' || $estado == 'CONFIRMADO') $class = 'badge-success';
            if ($estado == 'PENDIENTE') $class = 'badge-warning';
            if ($estado == 'CANCELADO') $class = 'badge-danger';
            
            return '<span class="badge ' . $class . ' px-2 py-1 shadow-sm" style="border-radius:10px;">' . $estado . '</span>';
        },
    ],
    [
        'class' => 'kartik\grid\DataColumn',
        // Quitamos 'attribute' para que NO tenga filtro
        'label' => 'Fecha / Horario',
        'format' => 'raw',
        'value' => function($model) {
            return '<div class="text-dark font-weight-bold">' . date("d/m/Y", strtotime($model->fecha_evento)) . '</div>' .
                   '<small class="text-muted"><i class="far fa-clock"></i> ' . $model->hora_inicio . ' - ' . $model->hora_fin . '</small>';
        },
    ],
    [
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'cliente_id', // FILTRO ACTIVO
        'label' => 'Cliente / Facturación',
        'format' => 'raw',
        'value' => function($model) {
            $cliente = $model->cliente; 
            $nombre = $cliente ? ($cliente->razon_social ?: $cliente->cliente_nombre) : 'N/A';
            $id = $cliente ? $cliente->identificacion : '---';
            
            return '<div class="text-primary font-weight-bold" style="font-size: 0.95rem;">' . Html::encode($nombre) . '</div>' .
                   '<small class="text-muted"><i class="fas fa-id-card mr-1"></i>' . Html::encode($id) . '</small>';
        },
    ],
    [
        'class' => 'kartik\grid\DataColumn',
        'attribute' => 'tipo_evento_id', // FILTRO ACTIVO
        'label' => 'Evento / Pax',
        'format' => 'raw',
        'value' => function($model) {
            $tipo = $model->tipoEvento ? $model->tipoEvento->nombre : 'Sin tipo';
            return '<strong>' . Html::encode($tipo) . '</strong><br>' .
                   '<span class="badge badge-light border text-muted">👥 ' . $model->cantidad_personas . ' Pax</span>';
        },
    ],
    [
        'class' => 'kartik\grid\DataColumn',
        // Quitamos 'attribute' para que NO tenga filtro
        'label' => 'Alertas',
        'format' => 'raw',
        'value' => function($model) {
            $html = '';
            if ($model->observaciones) {
                $html .= '<div class="small text-warning font-weight-bold" title="'.Html::encode($model->observaciones).'"><i class="fas fa-utensils"></i> Cocina...</div>';
            }
            if ($model->equipos_audiovisuales) {
                $html .= '<div class="small text-info font-weight-bold" title="'.Html::encode($model->equipos_audiovisuales).'"><i class="fas fa-microphone"></i> IT/Audio...</div>';
            }
            return $html ?: '<span class="text-muted small italic">Sin notas</span>';
        },
    ],
    [
        'class' => 'kartik\grid\DataColumn',
        'label' => 'Acceso Directo',
        'format' => 'raw',
        'hAlign' => 'center',
        'value' => function($model) {
            $url = Yii::$app->urlManager->createAbsoluteUrl(['/reservas/reservas/view', 'id' => $model->id]);
            return '<div class="input-group input-group-sm" style="width: 150px; margin: 0 auto;">
                        <input type="text" class="form-control" value="' . $url . '" id="link-input-' . $model->id . '" readonly style="background: #fdfdfd; font-size: 0.7rem;">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="button" onclick="copyToClipboard(' . $model->id . ')" title="Copiar">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>';
        },
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'width' => '120px',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'viewOptions' => [
            'role' => 'modal-remote',
            'title' => 'Ver Detalle',
            'data-toggle' => 'tooltip',
            'class' => 'btn btn-sm btn-outline-info rounded-circle ml-1',
        ],
        'updateOptions' => [
            'role' => 'modal-remote',
            'title' => 'Editar',
            'data-toggle' => 'tooltip',
            'class' => 'btn btn-sm btn-outline-primary rounded-circle ml-1',
        ],
        'deleteOptions' => [
            'role' => 'modal-remote',
            'title' => 'Eliminar',
            'class' => 'btn btn-sm btn-outline-danger rounded-circle ml-1',
            'data-confirm' => false,
            'data-method' => false,
            'data-request-method' => 'post',
            'data-toggle' => 'tooltip',
            'data-confirm-title' => '¿Confirmar eliminación?',
            'data-confirm-message' => '¿Estás seguro de eliminar esta reserva?',
        ],
    ],
];