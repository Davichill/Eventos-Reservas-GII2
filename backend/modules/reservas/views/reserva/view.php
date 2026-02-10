<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "Expediente #" . $model->id;

// Cálculos de saldo
$total_evento = $model->total_evento ?? 0;
$total_pagado = array_sum(array_column($pagos, 'monto'));
$saldo_pendiente = $total_evento - $total_pagado;
?>

<div class="reserva-view">
    <div class="detail-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div class="detail-title">
            <h1><?= Html::encode($this->title) ?> 
                <span class="badge badge-<?= strtolower($model->estado) ?>"><?= $model->estado ?></span>
            </h1>
            <p><i class="far fa-calendar"></i> <?= Yii::$app->formatter->asDate($model->fecha_evento, 'php:d/m/Y') ?></p>
        </div>
        <div class="detail-actions">
            <?= Html::a('<i class="fas fa-edit"></i> Editar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fas fa-file-pdf"></i> PDF', ['generar-pdf', 'id' => $model->id], ['class' => 'btn btn-danger', 'target' => '_blank']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-file-invoice"></i> Información del Evento</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="text-muted">Razón Social / Cliente</label>
                            <p class="font-weight-bold text-success" style="font-size: 1.1rem;">
                                <?= Html::encode($model->cliente ? $model->cliente->razon_social : $model->cliente_nombre) ?>
                            </p>
                            
                            <label class="text-muted">Salón Asignado</label>
                            <p><i class="fas fa-door-open"></i> <?= Html::encode($model->salon ? $model->salon->nombre_salon : 'Sin salón') ?></p>
                        </div>
                        <div class="col-md-6">
                            <label class="text-muted">Horario</label>
                            <p><?= Yii::$app->formatter->asTime($model->hora_inicio, 'short') ?> a <?= Yii::$app->formatter->asTime($model->hora_fin, 'short') ?></p>
                            
                            <label class="text-muted">Personas (Pax)</label>
                            <p>👥 <?= $model->cantidad_personas ?> Pax</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-money-check-alt"></i> Gestión de Pagos</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h6>Total Evento</h6>
                                <h4 class="text-primary"><?= Yii::$app->formatter->asCurrency($total_evento) ?></h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h6>Total Pagado</h6>
                                <h4 class="text-success"><?= Yii::$app->formatter->asCurrency($total_pagado) ?></h4>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 border rounded bg-light">
                                <h6>Saldo Pendiente</h6>
                                <h4 class="text-danger"><?= Yii::$app->formatter->asCurrency($saldo_pendiente) ?></h4>
                            </div>
                        </div>
                    </div>

                    <h6>Historial de Pagos</h6>
                    <div class="list-group">
                        <?php if (!empty($pagos)): ?>
                            <?php foreach ($pagos as $pago): ?>
                                <div class="list-group-item list-group-item-action flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?= $pago->tipo_pago ?> <small class="badge badge-info"><?= $pago->metodo_pago ?></small></h6>
                                        <span class="text-success font-weight-bold"><?= Yii::$app->formatter->asCurrency($pago->monto) ?></span>
                                    </div>
                                    <small class="text-muted"><i class="far fa-calendar"></i> <?= Yii::$app->formatter->asDatetime($pago->fecha_pago) ?></small>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-center text-muted py-3">No hay pagos registrados.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-warning mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-utensils"></i> Menú Seleccionado</h5>
                </div>
                <div class="card-body">
                    <p><strong>Opción:</strong> <?= $model->menu_opcion ?: 'No definida' ?></p>
                    <hr>
                    <label class="text-muted">Observaciones de Cocina:</label>
                    <div class="alert alert-warning" style="font-size: 0.9rem;">
                        <?= $model->observaciones ? nl2br(Html::encode($model->observaciones)) : 'Sin notas críticas.' ?>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Logística</h5>
                </div>
                <div class="card-body" style="font-size: 0.9rem;">
                    <p><strong>Equipos:</strong> <?= Html::encode($model->equipos_audiovisuales ?: 'Estándar') ?></p>
                    <p><strong>Mantelería:</strong> <?= Html::encode($model->manteleria) ?></p>
                    <p><strong>Servilletas:</strong> <?= Html::encode($model->color_servilleta) ?></p>
                </div>
            </div>
        </div>
    </div>
</div>