<?php
use yii\helpers\Url;
use yii\helpers\Json;

$this->title = 'Panel de Control | GO Quito';

// Font Awesome
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

// Chart.js
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js', [
    'position' => \yii\web\View::POS_END
]);

// Dashboard JS
$this->registerJsFile(Url::to('@web/js/graficos.js'), [
    'depends' => [\yii\web\JqueryAsset::class],
    'position' => \yii\web\View::POS_END
]);
?>

<div class="dashboard-index p-3">
    <!-- Estadísticas Rápidas -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3 id="eventosHoy">0</h3>
                    <p>Eventos Hoy</p>
                </div>
                <div class="icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3 id="ingresosProyectados">$0.00</h3>
                    <p>Ingresos Proyectados</p>
                    <small>Este mes</small>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3 id="eventosPendientes">0</h3>
                    <p>Eventos Pendientes</p>
                    <small>Próximos 7 días</small>
                </div>
                <div class="icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
        </div>
        
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3 id="ocupacionTotal">0%</h3>
                    <p>Ocupación</p>
                    <small>Salones hoy</small>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <!-- Estado de Cobros -->
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie"></i> Estado de Cobros
                    </h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                Período
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item btn-periodo-cobros active" onclick="Dashboard.cambiarPeriodoCobros('7dias', this)">Últimos 7 días</a>
                                <a href="#" class="dropdown-item btn-periodo-cobros" onclick="Dashboard.cambiarPeriodoCobros('30dias', this)">Últimos 30 días</a>
                                <a href="#" class="dropdown-item btn-periodo-cobros" onclick="Dashboard.cambiarPeriodoCobros('mes', this)">Este mes</a>
                                <a href="#" class="dropdown-item btn-periodo-cobros" onclick="Dashboard.cambiarPeriodoCobros('todos', this)">Todo</a>
                            </div>
                        </div>
                        <button type="button" class="btn btn-tool" onclick="Dashboard.actualizarTodo()">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="pagoPieChart" style="min-height: 250px; height: 250px;"></canvas>
                    
                    <div class="mt-4">
                        <div class="d-flex justify-content-between border-bottom pb-2">
                            <span><i class="fas fa-check-circle text-success"></i> Total Pagado:</span>
                            <span id="totalPagado" class="font-weight-bold text-success">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-2 mt-2">
                            <span><i class="fas fa-clock text-warning"></i> Total Pendiente:</span>
                            <span id="totalPendiente" class="font-weight-bold text-warning">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span><i class="fas fa-chart-line text-primary"></i> Total Proyectado:</span>
                            <span id="totalProyectado" class="font-weight-bold text-primary">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ocupación por Salones -->
        <div class="col-md-6">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-building"></i> Ocupación por Salones
                    </h3>
                    <div class="card-tools">
                        <div class="btn-group">
                            <button type="button" class="btn btn-tool dropdown-toggle" data-toggle="dropdown">
                                Período
                            </button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item btn-periodo-ocupacion active" onclick="Dashboard.cambiarPeriodoOcupacion('todos', this)">Todo</a>
                                <a href="#" class="dropdown-item btn-periodo-ocupacion" onclick="Dashboard.cambiarPeriodoOcupacion('mes', this)">Este mes</a>
                                <a href="#" class="dropdown-item btn-periodo-ocupacion" onclick="Dashboard.cambiarPeriodoOcupacion('30dias', this)">Últimos 30 días</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="ocupacionSalonesChart" style="min-height: 250px; height: 250px;"></canvas>
                    
                    <!-- Leyenda -->
                    <div class="mt-3 text-center text-muted small">
                        <i class="fas fa-info-circle"></i> Horas totales de ocupación por salón
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Créditos -->
    <div class="row mt-3">
        <div class="col-12 text-center text-muted">
            <small>Datos actualizados en tiempo real • GO Quito</small>
        </div>
    </div>
</div>

<?php
// CONFIGURACIÓN CORREGIDA - con el nombre correcto de tu proyecto
$config = [
    'urls' => [
        'estadisticas' => Url::to(['/api-graficos/estadisticas-rapidas']),
        'cobros' => Url::to(['/api-graficos/estado-cobros']),
        'ocupacion' => Url::to(['/api-graficos/ocupacion-salones']),
    ]
];

$this->registerJs("
    window.AppConfig = " . Json::encode($config) . ";
    console.log('✅ AppConfig cargado:', window.AppConfig);
", \yii\web\View::POS_HEAD);
?>

<?php
// CSS FORZADO para que los canvas sean visibles
$this->registerCss("
    canvas {
        display: block !important;
        width: 100% !important;
        height: 300px !important;
        background: white !important;
        border: 2px solid #dee2e6 !important;
        border-radius: 8px !important;
        padding: 10px !important;
        margin-top: 10px !important;
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    #pagoPieChart, #ocupacionSalonesChart {
        min-height: 300px !important;
        max-height: 400px !important;
        background: white !important;
    }
    
    .card-body {
        overflow: visible !important;
        min-height: 350px !important;
    }
");
?>