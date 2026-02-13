<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap4\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\reservas\models\search\ClientesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Directorio de Clientes';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);
?>

<div class="clientes-index shadow-lg" style="border-radius: 20px; background-color: #fff; border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
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
                        Html::a('<i class="fas fa-plus mr-1"></i> Agregar Nuevo Cliente', ['create'], [
                            'role' => 'modal-remote', 
                            'title' => 'Crear nuevo Cliente', 
                            'class' => 'btn btn-warning font-weight-bold shadow-sm px-4 text-dark', 
                            'style' => 'border-radius: 10px; border: none;'
                        ]) . 
                        Html::a('<i class="fas fa-sync-alt"></i>', [''], [
                            'data-pjax' => 1, 
                            'class' => 'btn btn-outline-secondary ml-2', 
                            'title' => 'Actualizar Tabla',
                            'style' => 'border-radius: 10px; background: #fff;'
                        ]) 
                ],
                '{toggleData}', // Botón para ver todos o paginar
                '{export}',     // Botones de descarga (PDF, Excel, etc.)
            ],
            'striped' => false,
            'hover' => true,
            'condensed' => true,
            'responsive' => true,
            'bordered' => false,
            'panel' => [
                'type' => 'default', 
                'heading' => '<div class="d-flex align-items-center py-2">'.
                                '<div class="bg-warning p-2 rounded mr-3 text-white shadow-sm" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-users fa-lg"></i></div>'.
                                '<div>'.
                                    '<h4 class="mb-0 text-dark font-weight-bold">'.Html::encode($this->title).'</h4>'.
                                    '<p class="mb-0 text-muted small">Gestión administrativa de cartera de clientes</p>'.
                                '</div>'.
                             '</div>',
                'before' => '<div class="mb-2"></div>',

                'after' => false,
            ],
            'exportConfig' => [
                GridView::PDF => [
                    'label' => 'Exportar a PDF',
                    'icon' => 'fas fa-file-pdf',
                    'iconOptions' => ['class' => 'text-danger'],
                ],
                GridView::EXCEL => [
                    'label' => 'Exportar a Excel',
                    'icon' => 'fas fa-file-excel',
                    'iconOptions' => ['class' => 'text-success'],
                ],
            ],
            'pager' => [
                'class' => \yii\bootstrap4\LinkPager::class,
                'options' => ['class' => 'pagination justify-content-center pt-3'],
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
    /* Estética General Modernizada */
    .table thead th { 
        background-color: #f8f9fa !important;
        color: #495057 !important;
        font-weight: 700 !important;
        text-transform: uppercase; 
        font-size: 0.75rem !important; 
        letter-spacing: 0.5px; 
        border-bottom: 2px solid #edf2f9 !important;
        padding: 15px !important;
    }

    .table td { 
        padding: 15px !important; 
        vertical-align: middle !important;
        border-top: 1px solid #f1f3f9 !important;
    }

    /* Estilo de los botones del Toolbar (Exportar, Toggle, etc) */
    .btn-group .btn { 
        border-radius: 10px !important; 
        margin-left: 5px; 
        border: 1px solid #dee2e6 !important;
        background-color: #fff !important;
        color: #6c757d !important;
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        font-size: 0.9rem;
    }
    
    .btn-group .btn:hover { 
        background-color: #f8f9fa !important; 
        color: #333 !important; 
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }

    /* Estilo para los inputs de filtro */
    .filters input, .filters select {
        border-radius: 8px !important;
        border: 1px solid #dce1e7 !important;
        font-size: 0.85rem !important;
        padding: 5px 10px !important;
    }

    /* Ajuste de Paginación */
    .pagination .page-item.active .page-link {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
        color: #000 !important;
    }
    .pagination .page-link {
        border-radius: 8px !important;
        margin: 0 3px;
        color: #6c757d;
    }

    /* Estilo del Panel Header */
    .card-header, .panel-heading { 
        background: #fff !important; 
        border-bottom: 1px solid #f1f3f9 !important;
        padding: 1.5rem !important;
    }
</style>