// js/menu/almuerzo_cena.js

let globalMax = 0;
let maxDisplay = 0;
const mainCont = document.getElementById('contenedor-menu-cena');
let currentHoverItem = null;

function contarSeccionesDisponibles() {
    const gruposUnicos = new Set();
    const checkboxes = mainCont.querySelectorAll('input[type="checkbox"]');
    checkboxes.forEach(cb => gruposUnicos.add(cb.dataset.group));
    return gruposUnicos.size;
}

function actualizarPrevisualizacion(item) {
    if (!item || item.classList.contains('disabled')) return;
    
    const imgData = item.getAttribute('data-img');
    const nombre = item.getAttribute('data-nombre');
    const guarnicion = item.getAttribute('data-guarnicion');
    const vegetales = item.getAttribute('data-vegetales');
    const tiempo = item.getAttribute('data-tiempo');
    
    const previewImg = document.getElementById('preview-image');
    const previewTitle = document.getElementById('preview-title');
    const previewDetails = document.getElementById('preview-details');

    // Transición suave de imagen
    previewImg.style.opacity = '0.3';
    setTimeout(() => {
        previewImg.src = imgData;
        previewImg.style.opacity = '1';
    }, 150);

    previewTitle.textContent = nombre;

    if (tiempo === 'Plato Fuerte' && (guarnicion || vegetales)) {
        previewDetails.style.display = 'block';
        document.getElementById('detail-guarnicion').textContent = guarnicion || '-';
        document.getElementById('detail-vegetales').textContent = vegetales || '-';
    } else {
        previewDetails.style.display = 'none';
    }
}

function activarMenu(n) {
    const disponibles = contarSeccionesDisponibles();
    globalMax = Math.min(n, disponibles);
    maxDisplay = n;

    document.getElementById('max-selections').textContent = maxDisplay;
    mainCont.style.opacity = "1";
    mainCont.style.pointerEvents = "auto";

    // Resetear
    mainCont.querySelectorAll('input[type="checkbox"]').forEach(c => {
        c.checked = false;
        c.disabled = false;
        c.closest('.item-cena').classList.remove('disabled');
    });
    
    document.getElementById('selected-count').textContent = '0';
}

if (mainCont) {
    mainCont.addEventListener('mouseover', (e) => {
        const item = e.target.closest('.item-cena');
        if (item && item !== currentHoverItem) {
            actualizarPrevisualizacion(item);
            currentHoverItem = item;
        }
    });

    mainCont.addEventListener('change', (e) => {
        if (e.target.type !== 'checkbox') return;

        const checkbox = e.target;
        const grupo = checkbox.dataset.group;
        const seleccionados = mainCont.querySelectorAll('input:checked');

        if (seleccionados.length > globalMax) {
            checkbox.checked = false;
            alert(`Su plan permite máximo ${globalMax} selecciones.`);
            return;
        }

        // Bloquear otros platos del mismo tiempo (entrada, fuerte, etc)
        const delMismoGrupo = mainCont.querySelectorAll(`input[data-group="${grupo}"]`);
        delMismoGrupo.forEach(cb => {
            if (cb !== checkbox) {
                cb.disabled = checkbox.checked;
                cb.closest('.item-cena').classList.toggle('disabled', checkbox.checked);
            }
        });

        // Bloqueo global si llegó al máximo
        const todosLosChecks = mainCont.querySelectorAll('input[type="checkbox"]');
        if (seleccionados.length >= globalMax) {
            todosLosChecks.forEach(c => {
                if (!c.checked) {
                    c.disabled = true;
                    c.closest('.item-cena').classList.add('disabled');
                }
            });
        } else {
            // Desbloquear solo lo que no tenga grupo ya seleccionado
            todosLosChecks.forEach(c => {
                const hayMarcadoEnGrupo = mainCont.querySelector(`input[data-group="${c.dataset.group}"]:checked`);
                if (!hayMarcadoEnGrupo) {
                    c.disabled = false;
                    c.closest('.item-cena').classList.remove('disabled');
                }
            });
        }

        document.getElementById('selected-count').textContent = seleccionados.length;
    });
}