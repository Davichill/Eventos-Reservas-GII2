// js/menu/coctel.js

// Variable global para el estado del acordeón
const ACORDEON_STORAGE_KEY = 'acordeon_coctel_estado';

/**
 * Alterna el estado de una sección (abrir/cerrar)
 */
function toggleSeccion(elemento) {
    const seccion = elemento.parentElement;
    const seccionId = seccion.id;

    // Alternar clase
    seccion.classList.toggle('seccion-cerrada');

    // Guardar estado en localStorage
    guardarEstadoAcordeon();

    // También guardar el estado específico de esta sección
    const estaCerrada = seccion.classList.contains('seccion-cerrada');
    guardarEstadoSeccion(seccionId, estaCerrada);
}

/**
 * Guarda el estado completo del acordeón en localStorage
 */
function guardarEstadoAcordeon() {
    const secciones = document.querySelectorAll('.categoria-seccion');
    const estado = {};

    secciones.forEach(seccion => {
        const seccionId = seccion.id;
        estado[seccionId] = seccion.classList.contains('seccion-cerrada');
    });

    try {
        localStorage.setItem(ACORDEON_STORAGE_KEY, JSON.stringify(estado));
    } catch (e) {
        console.error('Error al guardar estado del acordeón:', e);
    }
}

/**
 * Guarda el estado de una sección específica
 */
function guardarEstadoSeccion(seccionId, estaCerrada) {
    try {
        const estadoActual = JSON.parse(localStorage.getItem(ACORDEON_STORAGE_KEY)) || {};
        estadoActual[seccionId] = estaCerrada;
        localStorage.setItem(ACORDEON_STORAGE_KEY, JSON.stringify(estadoActual));
    } catch (e) {
        console.error('Error al guardar estado de sección:', e);
    }
}

/**
 * Carga el estado guardado del acordeón al cargar la página
 */
function cargarEstadoAcordeon() {
    try {
        const estadoGuardado = localStorage.getItem(ACORDEON_STORAGE_KEY);
        if (!estadoGuardado) return;

        const estado = JSON.parse(estadoGuardado);
        const secciones = document.querySelectorAll('.categoria-seccion');

        secciones.forEach(seccion => {
            const seccionId = seccion.id;
            if (estado.hasOwnProperty(seccionId)) {
                if (estado[seccionId] === true) {
                    seccion.classList.add('seccion-cerrada');
                } else {
                    seccion.classList.remove('seccion-cerrada');
                }
            }
        });
    } catch (e) {
        console.error('Error al cargar estado del acordeón:', e);
    }
}

/**
 * Expande todas las secciones
 */
function expandirTodo() {
    const secciones = document.querySelectorAll('.categoria-seccion');
    secciones.forEach(seccion => {
        seccion.classList.remove('seccion-cerrada');
    });
    guardarEstadoAcordeon();
}

/**
 * Colapsa todas las secciones
 */
function colapsarTodo() {
    const secciones = document.querySelectorAll('.categoria-seccion');
    secciones.forEach(seccion => {
        seccion.classList.add('seccion-cerrada');
    });
    guardarEstadoAcordeon();
}

/**
 * Expande solo las secciones que tienen checkboxes seleccionados
 */
function expandirSeleccionados() {
    const secciones = document.querySelectorAll('.categoria-seccion');
    secciones.forEach(seccion => {
        const tieneSeleccionados = seccion.querySelector('.check-bocadito:checked');
        if (tieneSeleccionados) {
            seccion.classList.remove('seccion-cerrada');
        } else {
            seccion.classList.add('seccion-cerrada');
        }
    });
    guardarEstadoAcordeon();
}

// ============================================
// FUNCIONES PARA EL VISOR Y CHECKBOXES
// ============================================

function actualizarVisor(ruta, nombre) {
    const img = document.getElementById('img-visor');
    const txt = document.getElementById('nombre-bocado-visor');

    if (img && txt) {
        img.style.opacity = '0.7';
        setTimeout(() => {
            img.src = ruta;
            img.style.opacity = '1';
            txt.innerText = nombre;
            txt.classList.remove('lang-txt');
        }, 100);
    }
}

// Manejo de cambios en checkboxes
document.addEventListener('change', function (e) {
    if (e.target.classList.contains('check-bocadito')) {
        const limit = 6;
        const seleccionados = document.querySelectorAll('.check-bocadito:checked');
        const checks = document.querySelectorAll('.check-bocadito');

        // Actualizar contador
        const countElement = document.getElementById('count');
        if (countElement) {
            countElement.innerText = seleccionados.length;

            // Feedback visual
            if (seleccionados.length === limit) {
                countElement.style.color = '#e74c3c';
                countElement.style.fontWeight = 'bold';
                countElement.style.backgroundColor = '#fff3e0';
                countElement.style.padding = '2px 8px';
                countElement.style.borderRadius = '4px';
            } else if (seleccionados.length >= limit - 1) {
                countElement.style.color = '#f39c12';
                countElement.style.fontWeight = 'bold';
                countElement.style.backgroundColor = '';
                countElement.style.padding = '';
                countElement.style.borderRadius = '';
            } else {
                countElement.style.color = '';
                countElement.style.fontWeight = '';
                countElement.style.backgroundColor = '';
                countElement.style.padding = '';
                countElement.style.borderRadius = '';
            }
        }

        // Habilitar/deshabilitar checkboxes
        checks.forEach(c => {
            const parent = c.closest('.checkbox-item') || c.parentElement;

            if (seleccionados.length >= limit) {
                if (!c.checked) {
                    c.disabled = true;
                    if (parent) parent.classList.add('disabled-check');
                }
            } else {
                c.disabled = false;
                if (parent) parent.classList.remove('disabled-check');
            }
        });

        // Auto-expandir sección cuando se selecciona un item
        if (e.target.checked) {
            const seccion = e.target.closest('.categoria-seccion');
            if (seccion && seccion.classList.contains('seccion-cerrada')) {
                seccion.classList.remove('seccion-cerrada');
                guardarEstadoSeccion(seccion.id, false);
            }
        }
    }
});

function resetVisor() {
    const txt = document.getElementById('nombre-bocado-visor');
    if (txt) {
        txt.classList.add('lang-txt');
        txt.setAttribute('data-key', 'visor_instruccion');
        if (typeof traducirPagina === 'function') {
            traducirPagina(window.currentLang || 'es');
        }
    }
}

// ============================================
// INICIALIZACIÓN AL CARGAR LA PÁGINA
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    console.log('✅ Coctel JS cargado correctamente');

    // 1. Cargar estado guardado del acordeón
    cargarEstadoAcordeon();

    // 2. Configurar botones de control del acordeón
    const acordeonControls = document.querySelector('.acordeon-controls');
    if (!acordeonControls) {
        // Si no existen los botones en el HTML, crearlos dinámicamente
        const visorCard = document.querySelector('.visor-sticky-card');
        if (visorCard) {
            const controlsHtml = `
                <div class="acordeon-controls" style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="button" onclick="expandirTodo()" class="btn-acordeon" style="background: #27ae60; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.9rem;">
                        <i class="fas fa-expand"></i> Expandir Todo
                    </button>
                    <button type="button" onclick="colapsarTodo()" class="btn-acordeon" style="background: #e74c3c; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.9rem;">
                        <i class="fas fa-compress"></i> Colapsar Todo
                    </button>
                    <button type="button" onclick="expandirSeleccionados()" class="btn-acordeon" style="background: #3498db; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; font-size: 0.9rem;">
                        <i class="fas fa-filter"></i> Solo Seleccionados
                    </button>
                </div>
            `;
            visorCard.insertAdjacentHTML('beforeend', controlsHtml);
        }
    }

    // 3. Inicializar contador
    const seleccionadosInicial = document.querySelectorAll('.check-bocadito:checked').length;
    const countElement = document.getElementById('count');
    if (countElement) {
        countElement.innerText = seleccionadosInicial;
    }

    // 4. Verificar si hay checkboxes seleccionados y expandir sus secciones
    if (seleccionadosInicial > 0) {
        setTimeout(expandirSeleccionados, 500);
    }

    // 5. Asegurar que todas las secciones tengan ID si no lo tienen
    const seccionesSinId = document.querySelectorAll('.categoria-seccion:not([id])');
    seccionesSinId.forEach((seccion, index) => {
        seccion.id = 'cat-seccion-' + index;
    });

    // 6. Configurar estado inicial de checkboxes deshabilitados
    if (seleccionadosInicial >= 6) {
        document.querySelectorAll('.check-bocadito:not(:checked)').forEach(c => {
            c.disabled = true;
            const parent = c.closest('.checkbox-item');
            if (parent) parent.classList.add('disabled-check');
        });
    }
});

// Guardar estado antes de recargar la página
window.addEventListener('beforeunload', function () {
    guardarEstadoAcordeon();
});