<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\grid\GridView;
use johnitvn\ajaxcrud\CrudAsset;
use johnitvn\ajaxcrud\BulkButtonWidget;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\reservas\models\search\ReservasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Reservas';
$this->params['breadcrumbs'][] = $this->title;

CrudAsset::register($this);

?>
<div class="reservas-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id' => 'crud-datatable',
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'pjax' => true,
            'columns' => require(__DIR__ . '/_columns.php'),
            // Cambia esto en tu sección de 'toolbar'
            'toolbar' => [
                [
                    'content' =>
                        Html::a(
                            '<i class="fas fa-plus"></i>',
                            ['create'], // Cambiado de glyphicon-plus a fas fa-plus
                            ['role' => 'modal-remote', 'title' => 'Crear nuevo Cliente', 'class' => 'btn btn-success']
                        ) . // Cambié btn-default por btn-success para que se vea mejor
                        Html::a(
                            '<i class="fas fa-sync"></i>',
                            [''], // Cambiado de glyphicon-repeat a fas fa-sync
                            ['data-pjax' => 1, 'class' => 'btn btn-default', 'title' => 'Reiniciar']
                        ) .
                        '{toggleData}' .
                        '{export}'
                ],
            ],
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'panel' => [
                'type' => 'primary',
                'heading' => '<i class="glyphicon glyphicon-list"></i> Reservas listing',
                'before' => '<em>* Resize table columns just like a spreadsheet by dragging the column edges.</em>',
                'after' => BulkButtonWidget::widget([
                    'buttons' => Html::a(
                        '<i class="glyphicon glyphicon-trash"></i>&nbsp; Delete All',
                        ["bulk-delete"],
                        [
                            "class" => "btn btn-danger btn-xs",
                            'role' => 'modal-remote-bulk',
                            'data-confirm' => false,
                            'data-method' => false,// for overide yii data api
                            'data-request-method' => 'post',
                            'data-confirm-title' => 'Are you sure?',
                            'data-confirm-message' => 'Are you sure want to delete this item'
                        ]
                    ),
                ]) .
                    '<div class="clearfix"></div>',
            ]
        ]) ?>
    </div>
</div>
<?php Modal::begin([
    "id" => "ajaxCrudModal",
    "footer" => "",// always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<?php
$js = <<< JS
function copyToClipboard(id) {
    var copyText = document.getElementById("link-input-" + id);
    copyText.select();
    copyText.setSelectionRange(0, 99999); // Para móviles

    navigator.clipboard.writeText(copyText.value).then(function() {
        // Opcional: un mensaje discreto con Krajee Toast o un alert
        alert("¡Link copiado!: " + copyText.value);
    }, function(err) {
        console.error('No se pudo copiar: ', err);
    });
}
// Guardamos la función en el objeto window para que sea global y accesible desde el onclick
window.copyToClipboard = copyToClipboard;
JS;
$this->registerJs($js, \yii\web\View::POS_END);
?>