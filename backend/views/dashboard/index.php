<?php
use yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Dashboard | GO Quito';

// 1. Configuración de URLs para el archivo JS
$config = [
    'urlEventos' => Url::to(['/api-graficos/eventos-por-dia']),
    'urlCobros' => Url::to(['/api-graficos/estado-cobros']),
    'urlPromedios' => Url::to(['/api-graficos/ingresos-por-tipo']),
    'urlCalendario' => Url::to(['/api-graficos/eventos-calendario']),
];
$this->registerJs("window.AppConfig = " . Json::encode($config) . ";", \yii\web\View::POS_HEAD);



// 2. Registro de librerías
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);
$this->registerJsFile('@web/js/graficos.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerCssFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js', [
    'position' => \yii\web\View::POS_HEAD
]);
?>

<div class="container-fluid" style="background: #f4f6f9; padding: 20px;">
    <div class="row">
        <div class="col-md-7">
            <div class="card shadow-sm border-0" style="border-radius: 15px; height: 100%;">
                <div class="card-body text-center">
                    <h4 class="font-weight-bold">Ocupación de Eventos por Día</h4>

                    <div class="btn-group btn-group-toggle mb-4" data-toggle="buttons">
                        <button class="btn btn-primary active shadow-sm" onclick="cargarDatos(7)">Próximos 7
                            días</button>
                        <button class="btn btn-light shadow-sm" onclick="cargarDatos(30)">Próximos 30 días</button>
                        <button class="btn btn-light shadow-sm" onclick="cargarDatos(180)">Próximos 6 meses</button>
                    </div>

                    <p class="text-muted" id="rangoTexto">Próximos 7 días</p>

                    <div style="height: 300px; position: relative;" class="mb-4">
                        <canvas id="eventosDiaChart"></canvas>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="card border-0 bg-light p-2 shadow-sm" style="border-radius: 10px;">
                                <small class="text-muted">Total Eventos</small>
                                <h3 class="mb-0 font-weight-bold" id="statTotal">0</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card border-0 bg-light p-2 shadow-sm" style="border-radius: 10px;">
                                <small class="text-muted">Días ocupados</small>
                                <h3 class="mb-0 font-weight-bold" id="statDias">0</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow-sm border-0" style="border-radius: 15px; height: 100%;">
                <div class="card-body text-center">
                    <h5 class="font-weight-bold">Estado de Cobros Totales</h5>

                    <div class="btn-group btn-group-sm mb-3">
                        <button class="btn btn-outline-primary active" onclick="cargarCobros('7')">7 días</button>
                        <button class="btn btn-outline-primary" onclick="cargarCobros('30')">30 días</button>
                        <button class="btn btn-outline-primary" onclick="cargarCobros('todos')">Todos</button>
                    </div>

                    <div style="height: 230px; position: relative;">
                        <canvas id="cobrosDonaChart"></canvas>
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -10%);">
                            <h3 class="font-weight-bold mb-0" id="centroPorcentaje">0%</h3>
                            <small class="text-muted">Cobrado</small>
                        </div>
                    </div>

                    <div class="mt-4 text-left">
                        <div class="p-2 mb-2 border-left border-info bg-light"
                            style="border-width: 5px !important; border-radius: 5px;">
                            <small class="text-muted">Total Proyectado</small>
                            <h5 class="font-weight-bold mb-0" id="lblProyectado">0,00 US$</h5>
                        </div>
                        <div class="p-2 mb-2 border-left border-success bg-light"
                            style="border-width: 5px !important; border-radius: 5px;">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Total Pagado</small>
                                <small class="text-success font-weight-bold" id="percPagado">0%</small>
                            </div>
                            <h5 class="font-weight-bold mb-0" id="lblPagado">0,00 US$</h5>
                        </div>
                        <div class="p-2 border-left border-danger bg-light"
                            style="border-width: 5px !important; border-radius: 5px;">
                            <div class="d-flex justify-content-between">
                                <small class="text-muted">Total Pendiente</small>
                                <small class="text-danger font-weight-bold" id="percPendiente">0%</small>
                            </div>
                            <h5 class="font-weight-bold mb-0" id="lblPendiente">0,00 US$</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm border-0 mt-4" style="border-radius: 15px;">
            <div class="card-body">
                <h5 class="font-weight-bold text-center mb-3">
                    <i class="fas fa-money-bill-wave"></i> Ingresos Promedio por Tipo
                </h5>

                <div class="d-flex justify-content-center mb-4">
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-primary" onclick="cargarIngresosTipo('7')">Últimos 7 días</button>
                        <button class="btn btn-light" onclick="cargarIngresosTipo('30')">Últimos 30 días</button>
                        <button class="btn btn-light" onclick="cargarIngresosTipo('todos')">Todos</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div style="height: 400px;">
                            <canvas id="ingresosTipoChart"></canvas>
                        </div>
                    </div>

                    <div class="col-md-6" id="contenedorTarjetasTipo" style="max-height: 400px; overflow-y: auto;">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mt-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body p-2">
                    <h6 class="font-weight-bold text-center mb-2">Agenda</h6>

                    <div id="calendarioEventos" style="font-size: 0.75rem;"></div>

                    <div class="d-flex justify-content-center mt-2 flex-wrap" style="font-size: 0.65rem;">
                        <span class="mx-1"><i class="fas fa-circle" style="color: #f1c40f;"></i> Pendiente</span>
                        <span class="mx-1"><i class="fas fa-circle" style="color: #2ecc71;"></i> Confirmada</span>
                        <span class="mx-1"><i class="fas fa-circle" style="color: #3498db;"></i> Completada</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>