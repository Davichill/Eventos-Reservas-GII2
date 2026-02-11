<?php
use yii\helpers\Html;
use yii\helpers\Json;

$this->title = 'Panel de Control | GO Quito';

/**
 * 1. REGISTRO DE RECURSOS (Assets)
 */
// Librería de Gráficos
$this->registerJsFile('https://cdn.jsdelivr.net/npm/chart.js', ['position' => \yii\web\View::POS_HEAD]);

// Estilos específicos (Asegúrate de que estas rutas existan en backend/web/css/...)
$this->registerCssFile('@web/css/graficos.css');

// Lógica de Gráficos (Depende de jQuery)
$this->registerJsFile('@web/js/graficos.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>

<div class="dashboard-index">
    <div class="main-content">
        <header class="top-bar mb-4" style="padding: 20px; background: white; border-bottom: 1px solid #eee;">
            <h2><i class="fas fa-chart-line"></i> Resumen de Ingresos y Pagos</h2>
        </header>

        <div class="dashboard-grid" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">

            <div class="card-grafico p-3 shadow-sm bg-white" id="card-pagos">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="m-0">Estado de Cobros Totales</h3>
                    <button class="btn btn-sm btn-outline-primary" onclick="actualizarTodo()">
                        <i class="fas fa-sync-alt"></i> Actualizar
                    </button>
                </div>

                <div class="filtros-container mb-3" id="filtros-pagos">
                    <button class="btn btn-xs btn-default active" onclick="cambiarFiltroPagos('7dias')" id="filtro-pagos-7dias">7 días</button>
                    <button class="btn btn-xs btn-default" onclick="cambiarFiltroPagos('30dias')" id="filtro-pagos-30dias">30 días</button>
                    <button class="btn btn-xs btn-default" onclick="cambiarFiltroPagos('todos')" id="filtro-pagos-todos">Todos</button>
                </div>

                <div class="row align-items-center">
                    <div class="col-md-6">
                        <canvas id="pagoPieChart"></canvas>
                    </div>

                    <div class="col-md-6">
                        <div class="info-box bg-light mb-2">
                            <div class="info-box-content">
                                <span class="info-box-text">Total Proyectado</span>
                                <span class="info-box-number h4" id="totalInfo">$0.00</span>
                                <small id="subtituloTotal">Cargando...</small>
                            </div>
                        </div>

                        <div id="contadorPagado" class="info-box bg-success mb-2" style="display: none;">
                            <div class="info-box-content">
                                <span class="info-box-text">Total Pagado</span>
                                <span class="info-box-number" id="totalPagado">$0.00</span>
                                <small id="porcentajePagado">0%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-grafico p-3 shadow-sm bg-white">
                <h3><i class="fas fa-building"></i> Ocupación por Salones</h3>
                <div class="filtros-container mb-3" id="filtros-salones">
                    <button class="btn btn-xs btn-default active" onclick="cambiarFiltroSalones('todos')">Todos</button>
                </div>
                <div style="height: 250px;">
                    <canvas id="ocupacionSalonesChart"></canvas>
                </div>
            </div>

            <div class="card-grafico p-3 shadow-sm bg-white" style="grid-column: span 2;">
                <h3>Ocupación de Eventos por Día</h3>
                <div class="filtros-container mb-3">
                    <button class="btn btn-xs btn-default active" onclick="cambiarFiltroEventos('7prox')">Próximos 7 días</button>
                </div>
                <div style="height: 300px;">
                    <canvas id="eventosDiaChart"></canvas>
                </div>
            </div>

            <div class="card-grafico p-3 shadow-sm bg-white" style="grid-column: span 2;">
                <div class="calendario-container">
                    <h3 class="text-center mb-4"><i class="fas fa-calendar-alt"></i> Calendario de Eventos</h3>
                    
                    <div class="calendario-header d-flex justify-content-between align-items-center mb-3">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-default" onclick="mesAnterior()">← Mes</button>
                        </div>
                        <div class="text-center">
                            <h4 id="titulo" class="m-0 text-uppercase"></h4>
                            <small id="anio" class="text-muted"></small>
                        </div>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-default" onclick="mesSiguiente()">Mes →</button>
                        </div>
                    </div>

                    <div class="calendario-grid-header" style="display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; font-weight: bold;">
                        <div>Lun</div><div>Mar</div><div>Mie</div><div>Jue</div><div>Vie</div><div>Sab</div><div>Dom</div>
                    </div>
                    <div id="dias" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px;"></div>
                </div>
            </div>

        </div>
    </div>
</div>

<?php
/**
 * 2. CONEXIÓN PHP -> JAVASCRIPT
 */
$datosJson = Json::encode($statsPago);

$script = <<< JS
    // Datos reales de la base de datos
    var datosPagos = $datosJson;

    $(document).ready(function() {
        // Inicializar los gráficos si la función existe en graficos.js
        if (typeof inicializarGraficos === 'function') {
            inicializarGraficos(datosPagos);
        }
        
        // Inicializar calendario si existe en tu JS
        if (typeof renderizarCalendario === 'function') {
            renderizarCalendario(); 
        }
    });
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>