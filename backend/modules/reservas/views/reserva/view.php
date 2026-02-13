<?php
use yii\helpers\Html;
use yii\helpers\Url;

/* Cálculos de saldo */
$total_evento = $model->total_evento ?? 0;
$total_pagado = array_sum(array_column($pagos, 'monto'));
$saldo_pendiente = $total_evento - $total_pagado;

// Configuración de color según estado
$badge_class = [
    'Confirmado' => 'badge-success',
    'Pendiente'  => 'badge-warning',
    'Cancelado'  => 'badge-danger',
    'Finalizado' => 'badge-info',
];
$estado_class = $badge_class[$model->estado] ?? 'badge-secondary';
?>

<div class="reserva-view container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4">
        <div>
            <span class="text-muted text-uppercase small font-weight-bold">Código de Reserva</span>
            <h4 class="mb-0 font-weight-bold">Expediente #<?= $model->id ?> 
                <span class="badge <?= $estado_class ?> ml-2 shadow-sm" style="font-size: 0.9rem;">
                    <?= strtoupper($model->estado) ?>
                </span>
            </h4>
        </div>
        <div class="detail-actions">
            <?= Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], [
                'class' => 'btn btn-outline-primary btn-sm rounded-circle', 
                'title' => 'Editar Registro',
                'role' => 'modal-remote' // Si usas AjaxCRUD para editar desde aquí
            ]) ?>
            <?= Html::a('<i class="fas fa-file-pdf"></i> PDF', ['generar-pdf', 'id' => $model->id], [
                'class' => 'btn btn-danger btn-sm ml-2 px-3', 
                'target' => '_blank',
                'data-pjax' => '0'
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4 bg-light">
                <div class="card-body">
                    <div class="row text-center text-md-left">
                        <div class="col-md-7 border-right">
                            <label class="text-muted small mb-0">Cliente / Razón Social</label>
                            <h5 class="text-dark font-weight-bold">
                                <?= Html::encode($model->cliente ? $model->cliente->razon_social : $model->cliente_nombre) ?>
                            </h5>
                            <p class="mb-0 mt-2 text-muted">
                                <i class="fas fa-door-open text-primary mr-1"></i> <?= Html::encode($model->salon ? $model->salon->nombre_salon : 'Sin salón') ?>
                            </p>
                        </div>
                        <div class="col-md-5">
                            <div class="mb-2">
                                <i class="far fa-calendar-alt text-danger mr-1"></i> 
                                <strong><?= Yii::$app->formatter->asDate($model->fecha_evento, 'php:d/m/Y') ?></strong>
                            </div>
                            <div class="small">
                                <i class="far fa-clock text-muted"></i> <?= Yii::$app->formatter->asTime($model->hora_inicio, 'short') ?> - <?= Yii::$app->formatter->asTime($model->hora_fin, 'short') ?>
                            </div>
                            <div class="small mt-1">
                                <i class="fas fa-users text-muted"></i> <?= $model->cantidad_personas ?> Personas (Pax)
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 font-weight-bold text-dark"><i class="fas fa-wallet text-warning mr-2"></i>Estado de Cuenta</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="p-2 text-center border-right">
                                <span class="text-muted small">Valor Total</span>
                                <h5 class="text-primary mb-0 font-weight-bold"><?= Yii::$app->formatter->asCurrency($total_evento) ?></h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 text-center border-right">
                                <span class="text-muted small">Abonado</span>
                                <h5 class="text-success mb-0 font-weight-bold"><?= Yii::$app->formatter->asCurrency($total_pagado) ?></h5>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-2 text-center">
                                <span class="text-muted small font-weight-bold">Saldo Pendiente</span>
                                <h5 class="text-danger mb-0 font-weight-bold font-italic"><?= Yii::$app->formatter->asCurrency($saldo_pendiente) ?></h5>
                            </div>
                        </div>
                    </div>

                    <label class="font-weight-bold small text-uppercase text-muted border-bottom d-block pb-1 mb-2">Historial de Pagos</label>
                    <div class="list-group list-group-flush">
                        <?php if (!empty($pagos)): ?>
                            <?php foreach ($pagos as $pago): ?>
                                <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center bg-transparent">
                                    <div>
                                        <div class="font-weight-bold small"><?= $pago->tipo_pago ?></div>
                                        <small class="text-muted font-italic"><?= Yii::$app->formatter->asDate($pago->fecha_pago) ?> • <?= $pago->metodo_pago ?></small>
                                    </div>
                                    <span class="text-success font-weight-bold"><?= Yii::$app->formatter->asCurrency($pago->monto) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-muted small py-2">No se registran transacciones.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm border-0 mb-4 overflow-hidden">
                <div class="card-header bg-warning py-2 text-center border-0">
                    <span class="font-weight-bold small text-dark"><i class="fas fa-utensils mr-2"></i>SERVICIO DE CATERING</span>
                </div>
                <div class="card-body">
                    <p class="text-center font-weight-bold text-dark mb-2"><?= $model->menu_opcion ?: 'Menú no definido' ?></p>
                    <div class="bg-light p-2 rounded border small text-muted italic" style="min-height: 80px;">
                        <i class="fas fa-quote-left fa-xs mr-1 text-warning"></i>
                        <?= $model->observaciones ? nl2br(Html::encode($model->observaciones)) : 'Sin requerimientos especiales de cocina.' ?>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 border-top-info">
                <div class="card-body p-3">
                    <h6 class="font-weight-bold border-bottom pb-2 mb-3"><i class="fas fa-truck-loading text-info mr-2"></i>Logística</h6>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Equipos:</span>
                        <span class="font-weight-bold"><?= Html::encode($model->equipos_audiovisuales ?: 'Estándar') ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small">
                        <span class="text-muted">Mantelería:</span>
                        <span class="font-weight-bold"><?= Html::encode($model->manteleria) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-0 small">
                        <span class="text-muted">Servilletas:</span>
                        <span class="font-weight-bold"><?= Html::encode($model->color_servilleta) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Ajustes específicos para que se vea bien dentro del modal */
    #ajaxCrudModal .modal-body { background-color: #f8f9fa; }
    .card { border-radius: 12px; }
    .border-top-info { border-top: 4px solid #17a2b8 !important; }
    .badge { letter-spacing: 0.5px; padding: 0.5em 1em; border-radius: 6px; }
</style>