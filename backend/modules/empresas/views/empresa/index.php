<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal; // Asegúrate de usar bootstrap4
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

$this->title = 'Gestión de Empresas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>

<div class="empresas-index shadow-lg"
    style="border-radius: 20px; background-color: #fff; border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
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
                        Html::a('<i class="fas fa-plus mr-1"></i> Nueva Empresa', ['create'], [
                            'role' => 'modal-remote',
                            'title' => 'Crear nueva Empresa',
                            'class' => 'btn btn-success font-weight-bold shadow-sm px-4 text-white',
                            'style' => 'border-radius: 10px; border: none; background-color: #28a745 !important;'
                        ]) .
                        Html::a('<i class="fas fa-sync-alt"></i>', [''], [
                            'data-pjax' => 1,
                            'class' => 'btn btn-outline-secondary ml-2',
                            'title' => 'Actualizar Tabla',
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
                'heading' => '<div class="d-flex align-items-center py-2">' .
                    '<div class="bg-primary p-2 rounded mr-3 text-white shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-building fa-lg"></i></div>' .
                    '<div>' .
                    '<h4 class="mb-0 text-dark font-weight-bold">' . Html::encode($this->title) . '</h4>' .
                    '<p class="mb-0 text-muted small">Administración y registro de entidades corporativas</p>' .
                    '</div>' .
                    '</div>',
                'before' => '<div class="mb-2"></div>',

                'after' => BulkButtonWidget::widget([
                    'buttons' => Html::a(
                        '<i class="fas fa-trash-alt mr-2"></i>Eliminar Seleccionados',
                        ["bulk-delete"],
                        [
                            "class" => "btn btn-outline-danger btn-sm px-3",
                            'role' => 'modal-remote-bulk',
                            'data-confirm' => false,
                            'data-method' => false,
                            'data-request-method' => 'post',
                            'data-confirm-title' => '¿Está seguro?',
                            'data-confirm-message' => '¿Desea eliminar los elementos seleccionados?',
                            'style' => 'border-radius: 8px;'
                        ]
                    ),
                ]) . '<div class="clearfix"></div>',
            ],
            'exportConfig' => [
                GridView::PDF => ['label' => 'Exportar PDF', 'iconOptions' => ['class' => 'text-danger']],
                GridView::EXCEL => ['label' => 'Exportar Excel', 'iconOptions' => ['class' => 'text-success']],
            ],
        ]) ?>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",
    "size" => "modal-lg",
    "options" => [
        "class" => "fade",
        "style" => "border-radius: 15px;"
    ],
]) ?>
<?php Modal::end(); ?>

<style>
    /* CONTENEDOR PRINCIPAL */
    .empresas-index {
        border-radius: 18px;
        background: #ffffff;
        border: none;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transition: all .3s ease;
    }

    /* HEADER DEL PANEL */
    .panel-heading {
        background: linear-gradient(135deg, #f8f9fa, #ffffff) !important;
        border-bottom: 1px solid #eef1f6 !important;
    }

    /* ICONO DEL HEADER */
    .bg-primary {
        background: linear-gradient(135deg, #4e73df, #224abe) !important;
        border-radius: 14px !important;
    }

    /* TITULO */
    .panel-heading h4 {
        font-size: 1.3rem;
        letter-spacing: 0.3px;
    }

    /* TABLA */
    .table {
        border-collapse: separate !important;
        border-spacing: 0 8px !important;
    }

    .table thead th {
        background: #f4f6fb !important;
        color: #6c757d !important;
        font-weight: 600 !important;
        font-size: 0.72rem !important;
        text-transform: uppercase;
        border: none !important;
        padding: 14px !important;
    }

    /* FILAS */
    .table tbody tr {
        background: #ffffff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        border-radius: 12px;
        transition: all .2s ease;
    }

    .table tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    .table td {
        border: none !important;
        padding: 16px !important;
        vertical-align: middle !important;
    }

    /* FILTROS */
    .filters input,
    .filters select {
        border-radius: 10px !important;
        border: 1px solid #e3e6f0 !important;
        padding: 6px 10px;
        transition: all .2s ease;
    }

    .filters input:focus,
    .filters select:focus {
        border-color: #4e73df !important;
        box-shadow: 0 0 0 0.1rem rgba(78, 115, 223, .15);
    }

    /* BOTÓN PRINCIPAL */
    .btn-success {
        background: linear-gradient(135deg, #1cc88a, #17a673) !important;
        border: none !important;
        border-radius: 10px !important;
        font-weight: 600;
        transition: all .2s ease;
    }

    .btn-success:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 14px rgba(28, 200, 138, .25);
    }

    /* BOTONES SECUNDARIOS */
    .btn-outline-secondary {
        border-radius: 10px !important;
    }

    /* BOTÓN BULK */
    .btn-outline-danger {
        border-radius: 10px !important;
        transition: all .2s ease;
    }

    .btn-outline-danger:hover {
        background: #e74a3b !important;
        color: #fff !important;
    }

    /* PAGINACIÓN */
    .pagination .page-link {
        border-radius: 10px !important;
        border: none;
        background: #f4f6fb;
        margin: 0 3px;
        color: #6c757d;
    }

    .pagination .active .page-link {
        background: #4e73df !important;
        color: #fff !important;
    }

    /* MODAL */
    .modal-content {
        border-radius: 18px !important;
        border: none !important;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        border-bottom: 1px solid #eef1f6 !important;
    }

    .modal-footer {
        border-top: 1px solid #eef1f6 !important;
    }
</style>