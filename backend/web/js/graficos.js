var chartOcupacion = null;
var chartCobros = null;
var chartIngresosTipo = null;

// --- GRÁFICO DE OCUPACIÓN ---
function cargarDatos(dias) {
    if (!window.AppConfig || !window.AppConfig.urlEventos) return;

    $.getJSON(window.AppConfig.urlEventos, { dias: dias }, function (res) {
        if (!res.success) return;

        const ctx = document.getElementById('eventosDiaChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(52, 152, 219, 0.3)');
        gradient.addColorStop(1, 'rgba(255, 255, 255, 0)');

        if (chartOcupacion) chartOcupacion.destroy();

        chartOcupacion = new Chart(ctx, {
            type: 'line',
            data: {
                labels: res.data.labels,
                datasets: [{
                    data: res.data.values,
                    borderColor: '#3498db',
                    fill: true,
                    backgroundColor: gradient,
                    tension: 0.4,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });

        $('#statTotal').text(res.data.values.reduce((a, b) => a + b, 0));
        $('#statDias').text(res.data.values.filter(v => v > 0).length);
        $('#rangoTexto').text('Próximos ' + dias + ' días');
    });
}

// --- GRÁFICO DE COBROS ---
function cargarCobros(filtro) {
    if (!window.AppConfig || !window.AppConfig.urlCobros) return;

    $.getJSON(window.AppConfig.urlCobros, { filtro: filtro }, function (res) {
        if (!res.success) return;
        const d = res.data;

        $('#lblProyectado').text(d.proyectado.toLocaleString('en-US') + ' US$');
        $('#lblPagado').text(d.pagado.toLocaleString('en-US') + ' US$');
        $('#lblPendiente').text(d.pendiente.toLocaleString('en-US') + ' US$');
        $('#percPagado').text(d.p_pagado + '%');
        $('#percPendiente').text(d.p_pend + '%');
        $('#centroPorcentaje').text(d.p_pagado + '%');

        const ctx = document.getElementById('cobrosDonaChart');
        if (chartCobros) chartCobros.destroy();

        chartCobros = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Pagado', 'Pendiente'],
                datasets: [{
                    data: [d.pagado, d.pendiente],
                    backgroundColor: ['#28a745', '#dc3545'],
                    borderWidth: 0
                }]
            },
            options: {
                cutout: '80%',
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }
            }
        });
    });
}

// --- GRÁFICO DE INGRESOS POR TIPO ---
function cargarIngresosTipo(filtro = 'todos') {
    // Usamos urlTipo que es el nombre que definimos en la vista
    const url = window.AppConfig.urlTipo || window.AppConfig.urlPromedios; 

    if (!url) {
        console.error("URL para Ingresos por Tipo no encontrada.");
        return;
    }

    $.getJSON(url, { filtro: filtro }, function (res) {
        if (!res.success) return;

        const ctx = document.getElementById('ingresosTipoChart');
        if (!ctx) return;

        if (chartIngresosTipo) chartIngresosTipo.destroy();

        chartIngresosTipo = new Chart(ctx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: res.labels,
                datasets: [{
                    label: 'Monto en USD',
                    data: res.values,
                    backgroundColor: '#3498db',
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                    x: { grid: { display: false } }
                },
                plugins: { legend: { display: false } }
            }
        });

        const contenedor = $('#contenedorTarjetasTipo');
        contenedor.empty();
        const colores = ['border-info', 'border-success', 'border-danger', 'border-warning'];

        res.detalles.forEach((item, index) => {
            const colorClass = colores[index % colores.length];
            const html = `
                <div class="card mb-2 bg-light border-left ${colorClass} shadow-sm" style="border-width: 5px !important; border-radius: 8px;">
                    <div class="card-body p-2 text-center">
                        <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem;">${item.nombre}</small>
                        <h5 class="font-weight-bold mb-0">${item.monto}</h5>
                        <small class="text-muted">${item.porcentaje}</small>
                    </div>
                </div>
            `;
            contenedor.append(html);
        });
    });
}

//Calendario Pequeño

function inicializarCalendario() {
    var calendarEl = document.getElementById('calendarioEventos');
    if (!calendarEl || typeof FullCalendar === 'undefined') return;

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prevYear,prev',
            center: 'title',
            right: 'next,nextYear'
        },
        height: 'auto',
        events: window.AppConfig.urlCalendario,
        displayEventTime: false, // No mostrar horas, solo la etiqueta
        eventContent: function(arg) {
            // Estilo personalizado para que parezca una etiqueta pequeña
            let arrayOfDomNodes = [ 
                $('<span>').text(arg.event.title).addClass('badge p-1 w-100')[0] 
            ];
            return { domNodes: arrayOfDomNodes };
        }
    });

    calendar.render();
}

$(document).ready(function () {
    // Cargamos el primero inmediatamente
    cargarDatos(7);

    // Retrasamos los demás unos milisegundos para no saturar los sockets
    setTimeout(function() {
        cargarCobros('todos');
    }, 200);

    setTimeout(function() {
        cargarIngresosTipo('todos');
    }, 400);

    setTimeout(function() {
        if(typeof inicializarCalendario === "function") {
            inicializarCalendario();
        }
    }, 600);
});