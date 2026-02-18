<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

$this->title = 'Gestión de Reservas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>

<div class="reservas-index shadow-lg mb-5" style="border-radius: 20px; background-color: #fff; border: 1px solid rgba(0,0,0,0.05);">
    <div id="ajaxCrudDatatable" class="p-2">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel, // Asegura que los filtros aparezcan sobre las columnas
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            'toolbar' => [
                [
                    'content' =>
                        Html::a(
                            '<i class="fas fa-plus mr-1"></i> Nueva Reserva',
                            ['create'],
                            [
                                'role' => 'modal-remote', 
                                'title' => 'Crear nueva Reserva', 
                                'class' => 'btn btn-success px-4 text-white shadow-sm', // Agregamos text-white para visibilidad
                                'style' => 'border-radius: 10px; font-weight: bold; background-color: #28a745 !important; border: none;'
                            ]
                        ) . 
                        Html::a(
                            '<i class="fas fa-sync-alt"></i>',
                            [''],
                            [
                                'data-pjax' => 1, 
                                'class' => 'btn btn-outline-secondary ml-2', 
                                'title' => 'Refrescar Tabla',
                                'style' => 'border-radius: 10px;'
                            ]
                        ) 
                ],
                '{toggleData}', // Mantiene botones de paginación
                '{export}',     // Mantiene botones de exportación (Excel, PDF)
            ],
            'striped' => false,
            'hover' => true,
            'condensed' => true,
            'responsive' => true,
            'bordered' => false,
            'panel' => [
                'type' => 'default', 
                'heading' => '<div class="d-flex align-items-center">'.
                                '<div class="bg-primary-light p-2 rounded mr-3" style="background: #eef2ff; color: #4e73df;"><i class="fas fa-calendar-check fa-lg"></i></div>'.
                                '<div>'.
                                    '<h4 class="mb-0 text-dark font-weight-bold">Panel de Reservas</h4>'.
                                    '<p class="mb-0 text-muted small">Filtre, exporte y gestione sus eventos</p>'.
                                '</div>'.
                             '</div>',
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
                            'data-confirm-message' => '¿Estás seguro de eliminar estos registros?',
                            'style' => 'border-radius: 8px;'
                        ]
                    ),
                ]) . '<div class="clearfix"></div>',
            ]
        ]) ?>
    </div>
</div>

<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "size" => "modal-xl", 
    "footer" => "", 
    "options" => [
        "tabindex" => false, 
        "style" => "border-radius: 15px;"
    ],
]) ?>
<?php Modal::end(); ?>
<?php
$script = <<< JS
    function copyToClipboard(id) {
        var copyText = document.getElementById("link-input-" + id);
        if (copyText) {
            copyText.select();
            copyText.setSelectionRange(0, 99999); // Soporte para móviles
            navigator.clipboard.writeText(copyText.value).then(function() {
                // Cambiar el icono o color temporalmente si usas SweetAlert o Toastr puedes ponerlo aquí
                alert("Link copiado al portapapeles");
            }).catch(function(err) {
                console.error('Error al copiar: ', err);
            });
        }
    }
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>
<style>
    /* Corrección del botón Crear para que siempre se vea texto blanco */
    .btn-success { color: #fff !important; }
    .btn-success:hover { background-color: #218838 !important; color: #fff !important; }

    /* Estilo de la barra de filtros (inputs superiores) */
    .filters input, .filters select {
        border-radius: 8px !important;
        border: 1px solid #d1d3e2 !important;
        padding: 6px !important;
        font-size: 0.85rem !important;
        background-color: #fdfdfd !important;
    }
    .filters input:focus {
        border-color: #4e73df !important;
        box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.1) !important;
    }

    /* Estilo general del Grid */
    .table thead th { 
        background-color: #f8f9fa !important;
        color: #4e71df !important;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 0.75rem;
        border-bottom: 2px solid #e3e6f0 !important;
    }

    .btn-group > .btn {
        border-radius: 8px !important;
        margin-left: 5px;
        background: #fff;
        border: 1px solid #d1d3e2;
        color: #6e707e;
    }

    .modal-content { border-radius: 20px !important; border: none; overflow: hidden; }
</style>