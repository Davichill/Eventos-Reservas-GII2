<?php
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ActiveDataProvider;

/* @var $this yii\web\View */
/* @var $model backend\modules\clientes\models\Clientes */

// 1. Validamos la existencia de la relación para evitar errores de ejecución
$hasRelation = $model->hasMethod('getEventos');

$dataProviderEventos = new ActiveDataProvider([
    'query' => $hasRelation ? $model->getEventos() : $model->find()->where(['id' => -1]),
    'pagination' => [
        'pageSize' => 5,
    ],
    'sort' => false, // Desactivado para evitar errores de columnas no encontradas
]);
?>

<div class="clientes-view container-fluid p-2">
    <div class="row">
        <div class="col-md-7">
            <h6 class="text-uppercase text-muted font-weight-bold mb-3 border-bottom pb-2">
                <i class="fas fa-user-tie text-primary mr-2"></i> Información General del Cliente
            </h6>
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-sm table-hover border-0'],
                'attributes' => [
                    [
                        'label' => 'Nombre del Cliente',
                        'value' => strtoupper($model->cliente_nombre . ' ' . $model->cliente_apellido),
                        'contentOptions' => ['class' => 'font-weight-bold text-dark', 'style' => 'font-size: 1.1rem;'],
                    ],
                    [
                        'attribute' => 'identificacion',
                        'label' => 'Cédula / RUC',
                        'contentOptions' => ['class' => 'text-primary font-weight-bold']
                    ],
                    [
                        'label' => 'Empresa Vinculada',
                        'format' => 'raw',
                        'value' => $model->empresa ? 
                            '<i class="fas fa-building mr-1 text-muted"></i>' . $model->empresa->razon_social : 
                            '<span class="text-muted font-italic">Particular</span>',
                    ],
                    'direccion_fiscal:ntext',
                    'ciudad',
                    'pais',
                    [
                        'attribute' => 'estado',
                        'format' => 'raw',
                        'value' => function($model) {
                            $isActivo = (trim($model->estado) === 'Activo');
                            $class = $isActivo ? 'badge-success' : 'badge-danger';
                            return "<span class='badge $class px-3 py-1' style='border-radius:12px;'>" . strtoupper($model->estado) . "</span>";
                        }
                    ],
                ],
            ]) ?>
        </div>

        <div class="col-md-5">
            <h6 class="text-uppercase text-muted font-weight-bold mb-3 border-bottom pb-2">
                <i class="fas fa-info-circle text-success mr-2"></i> Detalles de Contacto
            </h6>
            <?= DetailView::widget([
                'model' => $model,
                'options' => ['class' => 'table table-sm table-hover border-0'],
                'attributes' => [
                    'cliente_email:email',
                    'cliente_telefono',
                    'correo_facturacion:email',
                    [
                        'label' => 'Gestionado por',
                        'value' => $model->usuarioCreador ? $model->usuarioCreador->usuario : 'ID: ' . $model->id_usuario_creador,
                        'contentOptions' => ['class' => 'text-muted small font-italic'],
                    ],
                    [
                        'attribute' => 'fecha_registro',
                        'label' => 'Alta en Sistema',
                        'format' => ['date', 'php:d/m/Y H:i']
                    ],
                ],
            ]) ?>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h6 class="text-uppercase text-muted font-weight-bold mb-3 border-bottom pb-2">
                <i class="fas fa-history text-warning mr-2"></i> Historial de Actividad
            </h6>
            
            <div class="table-responsive shadow-sm" style="border-radius: 8px; border: 1px solid #eee;">
                <?= GridView::widget([
                    'dataProvider' => $dataProviderEventos,
                    'summary' => false,
                    'tableOptions' => ['class' => 'table table-striped table-hover m-0'],
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'label' => 'Evento / Descripción',
                            'value' => function($eventModel) {
                                // Buscamos cualquier campo que pueda tener el nombre del evento
                                return $eventModel->nombre_evento ?? $eventModel->nombre ?? $eventModel->descripcion ?? 'Sin descripción';
                            },
                            'contentOptions' => ['class' => 'font-weight-bold'],
                        ],
                        [
                            'label' => 'Fecha',
                            'value' => function($eventModel) {
                                $fecha = $eventModel->fecha_evento ?? $eventModel->fecha ?? $eventModel->created_at;
                                return $fecha ? Yii::$app->formatter->asDate($fecha, 'php:d/m/Y') : '-';
                            },
                        ],
                        [
                            'label' => 'Monto',
                            'value' => function($eventModel) {
                                return isset($eventModel->monto) ? '$' . number_format($eventModel->monto, 2) : '-';
                            }
                        ],
                        [
                            'attribute' => 'estado',
                            'format' => 'raw',
                            'value' => function($eventModel) {
                                $val = $eventModel->estado ?? 'Registrado';
                                return '<span class="badge badge-light border text-muted px-2">' . strtoupper($val) . '</span>';
                            }
                        ],
                    ],
                    'emptyText' => '<div class="p-4 text-center text-muted"><i class="fas fa-folder-open fa-2x mb-2"></i><br>No hay historial de eventos para este cliente.</div>',
                ]) ?>
            </div>
        </div>
    </div>
</div>

<style>
    .clientes-view .table th { background-color: #fcfcfc; border-top: 0 !important; color: #888; font-weight: 600; width: 40%; }
    .clientes-view .table td { border-top: 0 !important; vertical-align: middle; }
    .badge-success { background-color: #28a745 !important; color: white; }
    .badge-danger { background-color: #dc3545 !important; color: white; }
</style>