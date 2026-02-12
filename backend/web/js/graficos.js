/**
 * GO Quito - Dashboard VERSIÓN FINAL
 * CON DATOS REALES DE TUS MODELOS
 */

const Dashboard = (function() {
    'use strict';

    // Instancias de gráficos
    let charts = {
        pagoPie: null,
        ocupacionSalones: null
    };

    function init() {
        console.log('🚀 Dashboard con datos reales inicializando...');
        
        // Verificar AppConfig
        if (!window.AppConfig) {
            console.error('❌ AppConfig no encontrado');
            return;
        }
        
        console.log('📡 Conectando a API:', AppConfig.urls);
        
        // Cargar TODOS los datos reales
        cargarEstadisticasReales();
        cargarCobrosReales();
        cargarOcupacionReal();
        
        // Auto-actualizar cada 5 minutos
        setInterval(() => {
            console.log('🔄 Actualizando datos...');
            cargarEstadisticasReales();
            cargarCobrosReales();
            cargarOcupacionReal();
        }, 300000);
    }

    // ========================================
    // 1. ESTADÍSTICAS RÁPIDAS - DATOS REALES
    // ========================================
    function cargarEstadisticasReales() {
        $.ajax({
            url: AppConfig.urls.estadisticas,
            method: 'GET',
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                if (response.success) {
                    const d = response.data;
                    $('#eventosHoy').text(d.eventos_hoy || 0);
                    $('#ingresosProyectados').text('$' + formatearMoneda(d.ingresos_proyectados || 0));
                    $('#eventosPendientes').text(d.eventos_pendientes || 0);
                    $('#ocupacionTotal').text((d.ocupacion_promedio || 0) + '%');
                    console.log('✅ Estadísticas reales:', d);
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error cargando estadísticas:', error);
                // NO usar datos falsos, mostrar error
                $('#eventosHoy').text('?');
                $('#ingresosProyectados').text('Error');
                $('#eventosPendientes').text('?');
                $('#ocupacionTotal').text('?%');
            }
        });
    }

    // ========================================
    // 2. ESTADO DE COBROS - DATOS REALES
    // ========================================
    function cargarCobrosReales(periodo = '7dias') {
        const canvas = document.getElementById('pagoPieChart');
        if (!canvas) return;

        $.ajax({
            url: AppConfig.urls.cobros,
            data: { periodo: periodo },
            method: 'GET',
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                if (response.success) {
                    const d = response.data;
                    
                    // Actualizar textos
                    $('#totalPagado').text('$' + formatearMoneda(d.total_pagado || 0));
                    $('#totalPendiente').text('$' + formatearMoneda(d.total_pendiente || 0));
                    $('#totalProyectado').text('$' + formatearMoneda(d.total_proyectado || 0));
                    
                    // Crear gráfico
                    crearGraficoPagos(d.total_pagado || 0, d.total_pendiente || 0);
                    console.log('✅ Cobros reales - Pagado: $' + formatearMoneda(d.total_pagado));
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error cargando cobros:', error);
                $('#totalPagado').text('Error');
                $('#totalPendiente').text('Error');
                $('#totalProyectado').text('Error');
            }
        });
    }

    // ========================================
    // 3. OCUPACIÓN POR SALONES - DATOS REALES
    // ========================================
    function cargarOcupacionReal(periodo = 'todos') {
        const canvas = document.getElementById('ocupacionSalonesChart');
        if (!canvas) return;

        $.ajax({
            url: AppConfig.urls.ocupacion,
            data: { periodo: periodo },
            method: 'GET',
            dataType: 'json',
            timeout: 10000,
            success: function(response) {
                if (response.success) {
                    const d = response.data;
                    crearGraficoSalones(d.labels || [], d.horas || [], d.eventos || []);
                    console.log('✅ Ocupación real -', d.labels?.length || 0, 'salones');
                }
            },
            error: function(xhr, status, error) {
                console.error('❌ Error cargando ocupación:', error);
            }
        });
    }

    // ========================================
    // GRÁFICO DE PAGOS
    // ========================================
    function crearGraficoPagos(pagado, pendiente) {
        const canvas = document.getElementById('pagoPieChart');
        if (!canvas) return;
        
        if (charts.pagoPie) charts.pagoPie.destroy();
        
        // Si no hay datos, mostrar gráfico vacío
        if (pagado === 0 && pendiente === 0) {
            pagado = 1;
            pendiente = 1;
        }
        
        charts.pagoPie = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels: ['Pagado', 'Pendiente'],
                datasets: [{
                    data: [pagado, pendiente],
                    backgroundColor: ['#28a745', '#ffc107'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { position: 'bottom' },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw || 0;
                                const total = pagado + pendiente;
                                const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return `${context.label}: $${formatearMoneda(value)} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // ========================================
    // GRÁFICO DE SALONES
    // ========================================
    function crearGraficoSalones(labels, datos, eventos) {
        const canvas = document.getElementById('ocupacionSalonesChart');
        if (!canvas) return;
        
        if (charts.ocupacionSalones) charts.ocupacionSalones.destroy();
        
        // Si no hay datos, mostrar mensaje
        if (!labels || labels.length === 0) {
            labels = ['Sin datos'];
            datos = [0];
        }
        
        charts.ocupacionSalones = new Chart(canvas, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Horas de ocupación',
                    data: datos,
                    backgroundColor: generarColores(labels.length),
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            afterLabel: function(context) {
                                const index = context.dataIndex;
                                if (eventos && eventos[index]) {
                                    return `📅 Eventos: ${eventos[index]}`;
                                }
                                return '';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: { display: true, text: 'Horas' }
                    }
                }
            }
        });
    }

    // ========================================
    // FUNCIONES AUXILIARES
    // ========================================
    function formatearMoneda(valor) {
        return Number(valor).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    }

    function generarColores(cantidad) {
        const colores = [
            '#3498db', '#9b59b6', '#e74c3c', '#f39c12', 
            '#2ecc71', '#1abc9c', '#e67e22', '#e84342'
        ];
        return Array(cantidad).fill().map((_, i) => colores[i % colores.length]);
    }

    function cambiarPeriodoCobros(periodo, btn) {
        $('.btn-periodo-cobros').removeClass('active');
        $(btn).addClass('active');
        cargarCobrosReales(periodo);
    }

    function cambiarPeriodoOcupacion(periodo, btn) {
        $('.btn-periodo-ocupacion').removeClass('active');
        $(btn).addClass('active');
        cargarOcupacionReal(periodo);
    }

    function actualizarTodo() {
        cargarEstadisticasReales();
        cargarCobrosReales();
        cargarOcupacionReal();
    }

    // API pública
    return {
        init,
        actualizarTodo,
        cambiarPeriodoCobros,
        cambiarPeriodoOcupacion
    };
})();

// Inicializar cuando el DOM esté listo
$(document).ready(function() {
    if (typeof Chart !== 'undefined' && typeof AppConfig !== 'undefined') {
        Dashboard.init();
    }
});