<?php
use yii\helpers\Html;
use yii2fullcalendar\yii2fullcalendar;

$this->title = 'Calendario de Eventos - GO Quito';

// FORZAMOS LA CARGA DE LIBRERÍAS DESDE CDN
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment-with-locales.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/locale/es.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="calendario-index" style="padding: 20px; background: white; border-radius: 8px;">

    <h1 class="text-center" style="color: #001f3f;"><i class="fa fa-calendar"></i> <?= Html::encode($this->title) ?>
    </h1>
    <hr>

    <div id="calendario-wrapper" style="min-height: 500px;">
        <?= yii2fullcalendar::widget([
            'events' => $eventos,
            'clientOptions' => [
                'locale' => 'es',
                'height' => 'auto',
                'header' => [
                    'left' => 'prev,next today',
                    'center' => 'title',
                    'right' => 'month,agendaWeek,agendaDay'
                ],

                // --- AQUÍ INICIA EL EVENT CLICK ---
                'eventClick' => new \yii\web\JsExpression("
    function(calEvent, jsEvent, view) {
        // 1. Título del modal
        $('#modalTitle').html(calEvent.title);
        
        // 2. Extraer datos del controlador (vienen en el objeto directamente)
        var cliente = calEvent.cliente || 'No especificado';
        var salon = calEvent.salon || 'N/A';
        var pax = calEvent.pax || '0';
        var notas = calEvent.notas || 'Sin observaciones';
        
        // 3. Formatear fechas
        var inicio = calEvent.start.format('DD-MM-YYYY HH:mm');
        var fin = calEvent.end ? calEvent.end.format('DD-MM-YYYY HH:mm') : 'No definida';
        
        // 4. Construir el HTML con diseño limpio (Bootstrap 4)
        var html = '<div class=\"container-fluid\">' +
            '<div class=\"row\">' +
                '<div class=\"col-md-6\">' +
                    '<p><strong>👤 Cliente:</strong><br>' + cliente + '</p>' +
                    '<p><strong>🏛️ Salón:</strong><br>' + salon + '</p>' +
                '</div>' +
                '<div class=\"col-md-6\">' +
                    '<p><strong>👥 Personas:</strong><br>' + pax + '</p>' +
                    '<p><strong>⏰ Horario:</strong><br>' + inicio + ' a ' + fin + '</p>' +
                '</div>' +
            '</div>' +
            '<hr>' +
            '<div class=\"row\">' +
                '<div class=\"col-12\">' +
                    '<strong>📝 Notas/Observaciones:</strong>' +
                    '<div class=\"p-2 mt-1 border rounded bg-light\">' + notas + '</div>' +
                '</div>' +
            '</div>' +
            '<div class=\"text-right mt-3\">' +
                '<a href=\"index.php?r=reservas/reserva/view&id=' + calEvent.id + '\" class=\"btn btn-info btn-sm\"><i class=\"fa fa-eye\"></i> Ver Reserva</a> ' +
                '<a href=\"index.php?r=reservas/reserva/update&id=' + calEvent.id + '\" class=\"btn btn-warning btn-sm\"><i class=\"fa fa-edit\"></i> Editar</a>' +
            '</div>' +
        '</div>';
        
        $('#modalBody').html(html);
        $('#modalEvento').modal('show');
    }
"),
                // --- AQUÍ TERMINA EL EVENT CLICK ---
            ],
        ]); ?>
    </div>
</div>
<?php
use yii\bootstrap4\Modal; // Asegúrate de usar bootstrap4 o bootstrap dependiendo de tu versión

Modal::begin([
    'title' => '<h4 id="modalTitle"></h4>',
    'id' => 'modalEvento',
    'size' => 'modal-lg',
]);

echo '<div id="modalBody"></div>';
echo '<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>';

Modal::end();
?>