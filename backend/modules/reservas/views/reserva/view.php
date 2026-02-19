<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* Cálculos de saldo */
$total_evento = $model->total_evento ?? 0;
// Si no tienes una relación 'pagos' definida, puedes usar el campo total_pagado de la tabla
$total_pagado = $model->total_pagado ?? 0;
$saldo_pendiente = $total_evento - $total_pagado;

// Configuración de color según estado
$badge_class = [
    'Confirmada' => 'badge-success',
    'Pendiente' => 'badge-warning',
    'Cancelada' => 'badge-danger',
];
$estado_class = $badge_class[$model->estado] ?? 'badge-secondary';
?>

<div class="reserva-view container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <span class="text-muted text-uppercase small font-weight-bold">Expediente de Reserva</span>
            <h4 class="mb-0 font-weight-bold">#<?= $model->id ?> - <?= Html::encode($model->nombre_evento) ?>
                <span class="badge <?= $estado_class ?> ml-2 shadow-sm" style="font-size: 0.9rem;">
                    <?= strtoupper($model->estado) ?>
                </span>
            </h4>
        </div>
        <div class="detail-actions">
            <?= Html::a('<i class="fas fa-edit"></i> Editar', ['update', 'id' => $model->id], [
                'class' => 'btn btn-outline-primary btn-sm rounded-pill px-3',
                'role' => 'modal-remote'
            ]) ?>
            <?= Html::a('<i class="fas fa-file-pdf"></i> PDF', ['generar-pdf', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm rounded-pill ml-2 px-3',
                'target' => '_blank'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">

            <div class="card shadow-sm border-0 mb-4 bg-light">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-7 border-right">
                            <label class="text-muted small mb-0">Cliente / Razón Social</label>
                            <h5 class="text-dark font-weight-bold">
                                <?= Html::encode($model->firma_nombre ?: $model->cliente_nombre) ?>
                            </h5>
                            <p class="mb-0 mt-2 text-muted small">
                                <i class="fas fa-id-card mr-1"></i> ID Fiscal:
                                <?= Html::encode($model->firma_identificacion ?: 'No registrado') ?> <br>
                                <i class="fas fa-door-open text-primary mr-1"></i>
                                <?= Html::encode($model->id_salon ? "Salón ID: " . $model->id_salon : 'Sin salón asignado') ?>
                            </p>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-2">
                                <i class="far fa-calendar-alt text-danger mr-1"></i>
                                <strong><?= Yii::$app->formatter->asDate($model->fecha_evento, 'php:d/m/Y') ?></strong>
                            </div>
                            <div class="small">
                                <i class="far fa-clock text-muted"></i> <?= $model->hora_inicio ?> -
                                <?= $model->hora_fin ?>
                            </div>
                            <div class="small mt-1">
                                <i class="fas fa-users text-muted"></i> <?= $model->cantidad_personas ?> Pax (Personas)
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 font-weight-bold text-dark"><i
                            class="fas fa-address-book text-primary mr-2"></i>Contacto del Evento</h6>
                </div>
                <div class="card-body py-2">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted small mb-0">Nombre del Contacto:</label>
                            <p class="font-weight-bold"><?= Html::encode($model->contacto_evento_nombre ?: 'N/A') ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-0">Teléfono de Contacto:</label>
                            <p class="font-weight-bold"><?= Html::encode($model->contacto_evento_telefono ?: 'N/A') ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted small mb-0">Teléfono de Contacto:</label>
                            <p class="font-weight-bold"><?= Html::encode($model->contacto_evento_telefono ?: 'N/A') ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-wallet text-warning mr-2"></i>Estado de
                        Cuenta</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4 border-right">
                            <span class="text-muted small">Total Evento</span>
                            <h5 class="text-primary mb-0 font-weight-bold">
                                <?= Yii::$app->formatter->asCurrency($total_evento) ?>
                            </h5>
                        </div>
                        <div class="col-md-4 border-right">
                            <span class="text-muted small">Total Pagado</span>
                            <h5 class="text-success mb-0 font-weight-bold">
                                <?= Yii::$app->formatter->asCurrency($total_pagado) ?>
                            </h5>
                        </div>
                        <div class="col-md-4">
                            <span class="text-muted small font-weight-bold">Saldo Pendiente</span>
                            <h5 class="text-danger mb-0 font-weight-bold">
                                <?= Yii::$app->formatter->asCurrency($saldo_pendiente) ?>
                            </h5>
                        </div>
                    </div>
                    <div class="mt-3 text-center">
                        <span class="badge badge-pill badge-light border">
                            Estado de Pago: <strong><?= $model->estado_pago ?></strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card shadow-sm border-0 border-top-info mb-4">
                <div class="card-body p-3">
                    <h6 class="font-weight-bold border-bottom pb-2 mb-3"><i
                            class="fas fa-truck-loading text-info mr-2"></i>Logística</h6>

                    <div class="mb-3">
                        <label class="text-muted small mb-0 d-block">Equipos Audiovisuales:</label>
                        <span
                            class="small font-weight-bold"><?= Html::encode($model->equipos_audiovisuales ?: 'No requeridos') ?></span>
                    </div>

                    <div class="row mb-3">
                        <div class="col-6 border-right">
                            <label class="text-muted small mb-0 d-block">Mantelería:</label>
                            <span
                                class="font-weight-bold small"><?= Html::encode($model->manteleria ?: 'Estándar') ?></span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small mb-0 d-block">Servilletas:</label>
                            <span
                                class="font-weight-bold small"><?= Html::encode($model->color_servilleta ?: 'Estándar') ?></span>
                        </div>
                    </div>

                    <div class="mb-0">
                        <label class="text-muted small mb-0 d-block">Planimetría:</label>
                        <?php
                        // Verificamos si la propiedad existe antes de intentar leerla
                        if ($model->hasAttribute('planimetria') && !empty($model->planimetria)): ?>
                            <span class="text-success small">
                                <i class="fas fa-check-circle"></i> Archivo cargado:
                                <?= Html::encode($model->planimetria) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted small font-italic">Sin archivo adjunto</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-warning py-2 text-center border-0">
                    <span class="font-weight-bold small text-dark">OBSERVACIONES</span>
                </div>
                <div class="card-body small">
                    <label class="font-weight-bold text-muted">Notas Generales:</label>
                    <p><?= $model->notas ? nl2br(Html::encode($model->notas)) : 'Sin notas.' ?></p>

                    <label class="font-weight-bold text-muted border-top pt-2 d-block">Detalle Logístico:</label>
                    <p><?= $model->logistica ? nl2br(Html::encode($model->logistica)) : 'Sin detalles adicionales.' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
// Buscamos los cambios registrados para esta reserva
$cambios = (new \yii\db\Query())
    ->select('*')
    ->from('reserva_historial')
    ->where(['id_reserva' => $model->id])
    ->orderBy('fecha_cambio DESC')
    ->all();
?>

<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-secondary text-white">
                <h6 class="mb-0"><i class="fas fa-history"></i> Historial de Versiones y Cambios</h6>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover table-striped mb-0 small">
                    <thead>
                        <tr>
                            <th>Fecha del Cambio</th>
                            <th>Datos Anteriores (Resumen)</th>
                            <th class="text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cambios as $cambio):
                            $json = json_decode($cambio['datos_anteriores'], true);
                            ?>
                            <tr>
                                <td><?= Yii::$app->formatter->asDatetime($cambio['fecha_cambio']) ?></td>
                                <td>
                                    <strong>Archivo:</strong> <?= Html::encode($cambio['motivo_cambio']) ?>
                                </td>
                                <td class="text-right">
                                    <?php if (strpos($cambio['motivo_cambio'], '.pdf') !== false): ?>
                                        <?= Html::a(
                                            '<i class="fas fa-file-pdf"></i> Ver Cotización',
                                            ['descargar-version', 'archivo' => $cambio['motivo_cambio']],
                                            ['class' => 'btn btn-xs btn-danger', 'target' => '_blank', 'data-pjax' => '0']
                                        )
                                            ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (empty($cambios)): ?>
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No hay cambios registrados en esta
                                    reserva.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    #ajaxCrudModal .modal-body {
        background-color: #f8f9fa;
    }

    .card {
        border-radius: 12px;
    }

    .border-top-info {
        border-top: 4px solid #17a2b8 !important;
    }

    .badge {
        padding: 0.5em 1em;
        border-radius: 6px;
    }
</style>