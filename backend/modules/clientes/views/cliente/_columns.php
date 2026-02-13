<?php
use yii\helpers\Url;
use yii\helpers\Html;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'identificacion',
        'label' => 'ID / RUC',
        'format' => 'raw',
        'vAlign' => 'middle',
        'width' => '150px',
        'value' => function($model) {
            return '<div class="text-left">' .
                        // Tamaño equilibrado: 1.05rem con un azul marino profesional
                        '<div style="font-size: 1.05rem; font-weight: 700; color: #002d5a; letter-spacing: 0.5px;">' . 
                            $model->identificacion . 
                        '</div>' .
                        '<div style="font-size: 0.7rem; color: #95a5a6; font-weight: 600; text-transform: uppercase;">Doc. Identidad</div>' .
                   '</div>';
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'razon_social',
        'label' => 'Empresa',
        'format' => 'raw',
        'vAlign' => 'middle',
        'value' => function($model) {
            return '<div>' . 
                        '<div class="text-dark" style="font-size: 1rem; font-weight: 600; line-height: 1.2;">' . $model->razon_social . '</div>' .
                        '<div class="text-muted small" style="font-size: 0.8rem;"><i class="fas fa-building mr-1 opacity-50"></i> Entidad Jurídica</div>' .
                   '</div>';
        },
    ],
    // NUEVA COLUMNA: NOMBRE DEL CLIENTE ASOCIADO
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'cliente_nombre',
        'label' => 'Cliente / Contacto',
        'format' => 'raw',
        'vAlign' => 'middle',
        'value' => function($model) {
            // Combinamos nombre y apellido si existen
            $nombreCompleto = trim($model->cliente_nombre . ' ' . $model->cliente_apellido);
            return '<div class="d-flex align-items-center">' .
                        '<div class="bg-info text-white rounded d-flex align-items-center justify-content-center mr-2 shadow-sm" style="width: 30px; height: 30px; font-size: 0.75rem; font-weight: bold;">' . 
                            strtoupper(substr($model->cliente_nombre, 0, 1)) . 
                        '</div>' .
                        '<div>' .
                            '<div class="text-dark font-weight-bold" style="font-size: 0.9rem;">' . ($nombreCompleto ?: 'No asignado') . '</div>' .
                            '<div class="text-muted small" style="font-size: 0.75rem;"><i class="fas fa-envelope mr-1"></i>' . $model->cliente_email . '</div>' .
                        '</div>' .
                   '</div>';
        },
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'direccion_fiscal',
        'label' => 'Dirección',
        'format' => 'raw',
        'vAlign' => 'middle',
        'value' => function($model) {
            return '<div style="max-width: 180px; font-size: 0.85rem;" class="text-secondary">' .
                        '<i class="fas fa-map-marker-alt text-danger mr-1 opacity-50"></i> ' . $model->direccion_fiscal . 
                   '</div>';
        },
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign' => 'middle',
        'width' => '140px',
        'template' => '<div class="btn-group shadow-sm border" style="border-radius: 6px; overflow: hidden;">{view}{update}{delete}</div>',
        'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to([$action, 'id' => $key]);
        },
        'buttons' => [
            'view' => function($url, $model) {
                return Html::a('<i class="fas fa-eye"></i>', $url, ['class' => 'btn btn-sm btn-white text-info', 'role' => 'modal-remote', 'title' => 'Ver']);
            },
            'update' => function($url, $model) {
                return Html::a('<i class="fas fa-pencil-alt"></i>', $url, ['class' => 'btn btn-sm btn-white text-primary', 'role' => 'modal-remote', 'title' => 'Editar']);
            },
            'delete' => function($url, $model) {
                return Html::a('<i class="fas fa-trash"></i>', $url, [
                    'class' => 'btn btn-sm btn-white text-danger',
                    'role' => 'modal-remote',
                    'data-confirm' => false, 'data-method' => false,
                    'data-request-method' => 'post',
                    'data-confirm-title' => 'Eliminar',
                    'data-confirm-message' => '¿Está seguro de eliminar esta empresa?'
                ]);
            },
        ],
    ],
];