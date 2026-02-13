<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal; // Cambiado a bootstrap4 para consistencia
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\pagos_reservas\models\search\PagosReservasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Pagos de Reservas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="pagos-reservas-index shadow-lg" style="border-radius: 20px; background-color: #fff; border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
    <div id="ajaxCrudDatatable" class="p-3">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container']],
            'toolbar' => [
                [
                    'content' =>
                        Html::a('<i class="fas fa-plus mr-1"></i> Nuevo Pago', ['create'], [
                            'role' => 'modal-remote', 
                            'title' => 'Registrar nuevo Pago', 
                            'class' => 'btn btn-success font-weight-bold shadow-sm px-4', 
                            'style' => 'border-radius: 10px; border: none; background-color: #28a745 !important;'
                        ]) . 
                        Html::a('<i class="fas fa-sync-alt"></i>', [''], [
                            'data-pjax' => 1, 
                            'class' => 'btn btn-outline-secondary ml-2', 
                            'title' => 'Actualizar',
                            'style' => 'border-radius: 10px; background: #fff;'
                        ]) 
                ],
                '{toggleData}',
                '{export}',
            ],
            'striped' => false,
            'hover' => true,
            'condensed' => true,
            'responsive' => true,
            'bordered' => false,
            'panel' => [
                'type' => 'default', 
                'heading' => '<div class="d-flex align-items-center py-2">'.
                                '<div class="bg-info p-2 rounded mr-3 text-white shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-file-invoice-dollar fa-lg"></i></div>'.
                                '<div>'.
                                    '<h4 class="mb-0 text-dark font-weight-bold">'.Html::encode($this->title).'</h4>'.
                                    '<p class="mb-0 text-muted small">Control y seguimiento de transacciones de reservas</p>'.
                                '</div>'.
                             '</div>',
                'before' => '<div class="mb-2"></div>',

                'after' => BulkButtonWidget::widget([
                    'buttons' => Html::a(
                        '<i class="fas fa-trash-alt mr-2"></i>Eliminar Selección',
                        ["bulk-delete"],
                        [
                            "class" => "btn btn-outline-danger btn-sm px-3",
                            'role' => 'modal-remote-bulk',
                            'data-confirm' => false,
                            'data-method' => false,
                            'data-request-method' => 'post',
                            'data-confirm-title' => 'Confirmación',
                            'data-confirm-message' => '¿Está seguro de eliminar los pagos seleccionados?',
                            'style' => 'border-radius: 8px;'
                        ]
                    ),
                ]) . '<div class="clearfix"></div>',
            ],
            'exportConfig' => [
                GridView::PDF => ['label' => 'Descargar PDF', 'iconOptions' => ['class' => 'text-danger']],
                GridView::EXCEL => ['label' => 'Descargar Excel', 'iconOptions' => ['class' => 'text-success']],
            ],
        ]) ?>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",
    "size" => "modal-lg", // String para evitar errores de constante
    "options" => [
        "class" => "fade",
        "style" => "border-radius: 15px;"
    ],
]) ?>
<?php Modal::end(); ?>

<style>

/* CONTENEDOR PRINCIPAL */
.pagos-reservas-index {
    border-radius: 18px;
    background: #ffffff;
    border: none;
    box-shadow: 0 12px 30px rgba(0,0,0,0.06);
    transition: all .3s ease;
}

/* HEADER DEL PANEL */
.panel-heading {
    background: linear-gradient(135deg, #f8fafc, #ffffff) !important;
    border-bottom: 1px solid #edf2f7 !important;
    padding: 1.5rem !important;
}

/* ICONO DEL HEADER */
.bg-info {
    background: linear-gradient(135deg, #0ea5e9, #0369a1) !important;
    border-radius: 14px !important;
}

/* TITULO */
.panel-heading h4 {
    font-size: 1.25rem;
    letter-spacing: .3px;
}

/* TABLA */
.table {
    border-collapse: separate !important;
    border-spacing: 0 6px !important;
}

/* ENCABEZADO */
.table thead th {
    background: #f1f5f9 !important;
    color: #64748b !important;
    font-weight: 600 !important;
    text-transform: uppercase;
    font-size: 0.70rem !important;
    letter-spacing: 1px;
    border: none !important;
    padding: 14px !important;
}

/* FILAS COMO CARDS */
.table tbody tr {
    background: #ffffff;
    box-shadow: 0 3px 10px rgba(0,0,0,0.04);
    border-radius: 12px;
    transition: all .2s ease;
}

.table tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

/* CELDAS */
.table td {
    border: none !important;
    padding: 14px !important;
    vertical-align: middle !important;
    font-size: 0.9rem;
    color: #334155;
}

/* FILTROS */
.filters input,
.filters select {
    border-radius: 10px !important;
    border: 1px solid #e2e8f0 !important;
    padding: 6px 10px !important;
    font-size: 0.85rem !important;
    transition: all .2s ease;
}

.filters input:focus,
.filters select:focus {
    border-color: #0ea5e9 !important;
    box-shadow: 0 0 0 0.1rem rgba(14,165,233,.15);
}

/* BOTÓN PRINCIPAL */
.btn-success {
    background: linear-gradient(135deg, #22c55e, #15803d) !important;
    border: none !important;
    border-radius: 10px !important;
    font-weight: 600;
    transition: all .2s ease;
}

.btn-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 15px rgba(34,197,94,.25);
}

/* BOTONES SECUNDARIOS */
.btn-outline-secondary {
    border-radius: 10px !important;
    border: 1px solid #e2e8f0 !important;
}

.btn-outline-secondary:hover {
    background: #f1f5f9 !important;
}

/* BOTÓN ELIMINAR */
.btn-outline-danger {
    border-radius: 10px !important;
    transition: all .2s ease;
}

.btn-outline-danger:hover {
    background: #dc2626 !important;
    color: #fff !important;
}

/* PAGINACIÓN MODERNA */
.pagination {
    margin-top: 10px;
}

.pagination .page-link {
    border-radius: 10px !important;
    border: none;
    background: #f1f5f9;
    margin: 0 3px;
    color: #0ea5e9;
    transition: all .2s ease;
}

.pagination .page-link:hover {
    background: #e0f2fe;
}

.pagination .active .page-link {
    background: #0ea5e9 !important;
    color: #fff !important;
}

/* EXPORT Y TOGGLE */
.btn-group .btn {
    border-radius: 10px !important;
    border: 1px solid #e2e8f0 !important;
    background: #fff !important;
    color: #64748b !important;
}

.btn-group .btn:hover {
    background: #f1f5f9 !important;
    color: #1e293b !important;
}

/* MODAL ELEGANTE */
.modal-content {
    border-radius: 18px !important;
    border: none !important;
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.modal-header {
    border-bottom: 1px solid #edf2f7 !important;
}

.modal-footer {
    border-top: 1px solid #edf2f7 !important;
}

</style>
