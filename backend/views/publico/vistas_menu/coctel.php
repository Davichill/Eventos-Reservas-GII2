<?php
use yii\helpers\Html;
use yii\helpers\Url;

// Cargar el archivo de idiomas
$idiomasPath = Yii::getAlias('@backend/web/idiomas.php');
if (file_exists($idiomasPath)) {
    require($idiomasPath);
    // Obtener el idioma actual de la sesión o usar español por defecto
    $lang = Yii::$app->session->get('lang', 'es');
    $t = $texts[$lang] ?? $texts['es'];
} else {
    // Fallback en caso de que no exista el archivo
    $t = [
        'menu' => 'Menú Plan Cóctel',
        'menu_plan_coctel_desc' => 'Seleccione 6 bocaditos. Use el visor a la derecha para ver la referencia de cada uno.',
        'vista_previa' => 'Vista Previa',
        'visor_instruccion' => 'Pase el mouse sobre un bocado',
        'seleccionados' => 'Seleccionados',
        'Expandir Todo' => 'Expandir Todo',
        'Colapsar Todo' => 'Colapsar Todo',
        'Solo Seleccionados' => 'Solo Seleccionados',
        'cat_bocados_salados' => 'BOCADOS SALADOS',
        'cat_vegetariano___vegano' => 'VEGETARIANO / VEGANO',
        'cat_mariscos_y_pescados' => 'MARISCOS Y PESCADOS',
        'cat_bocaditos_dulces' => 'BOCADITOS DULCES',
    ];
}

// SOLO UNA FORMA de cargar el CSS - Elige UNA:

// OPCIÓN 1: Usar registerCssFile (recomendado para Yii2)
$this->registerCssFile('@web/css/menu/coctel.css', [
    'depends' => [\yii\bootstrap4\BootstrapAsset::class],
    'position' => \yii\web\View::POS_HEAD
]);

// Registrar JS
$this->registerJsFile('@web/js/menu/coctel.js', [
    'depends' => [\yii\web\JqueryAsset::class],
    'position' => \yii\web\View::POS_END
]);
?>

<link rel="stylesheet" href="<?= Url::to('@web/css/menu/coctel.css') ?>">

<div class="coctel-layout-container">
    <div class="instruccion-sticky">
        <p>
            <strong><?= $t['menu'] ?? Yii::t('app', 'menu') ?></strong>
            <?= $t['menu_plan_coctel_desc'] ?? Yii::t('app', 'menu_plan_coctel_desc') ?>
        </p>
    </div>

    <div class="coctel-grid-cuerpo">
        <div class="opciones-columna-izq">
            <?php
            if (!function_exists('pintarEstructuraCoctel')) {
                function pintarEstructuraCoctel($categorias, $t)
                {
                    $imgDefault = Yii::getAlias('@web/img/no-image.png');

                    $index = 0;

                    foreach ($categorias as $nombreCat => $subcategorias) {
                        $keyCat = 'cat_' . strtolower(str_replace([' ', '/'], ['_', '_'], $nombreCat));
                        $tituloMostrar = $t[$keyCat] ?? $nombreCat;

                        $sectionId = 'cat-' . strtolower(str_replace([' ', '/'], ['-', '-'], $nombreCat));

                        echo '<div class="categoria-seccion" id="' . $sectionId . '" data-index="' . $index . '">';
                        echo '<div class="categoria-header" onclick="toggleSeccion(this)">';
                        echo '<h3 class="titulo-categoria-pdf" style="margin:0;">' . Html::encode($tituloMostrar) . '</h3>';
                        echo '<span class="flecha-toggle">▼</span>';
                        echo '</div>';

                        echo '<div class="categoria-contenido">';

                        foreach ($subcategorias as $nombreSub => $items) {
                            if ($nombreSub !== "General") {
                                echo '<h4 class="subtitulo-pdf">' . Html::encode($nombreSub) . '</h4>';
                            }

                            echo '<div class="grid-checks-bocaditos">';

                            foreach ($items as $item) {
                                // Convertir BLOB a base64 directamente
                                $imgUrl = !empty($item['imagen'])
                                    ? 'data:image/jpeg;base64,' . base64_encode($item['imagen'])
                                    : $imgDefault;
                                ?>
                                <div class="checkbox-item">
                                    <label class="label-click"
                                        onmouseover="actualizarVisor('<?= $imgUrl ?>', '<?= Html::encode($item['nombre']) ?>')"
                                        onmouseout="resetVisor()">
                                        <input type="checkbox" name="bocaditos[]" value="<?= Html::encode($item['nombre']) ?>"
                                            class="check-bocadito" data-categoria="<?= Html::encode($nombreCat) ?>">
                                        <span class="label-text"><?= Html::encode($item['nombre']) ?></span>
                                    </label>
                                </div>
                                <?php
                            }
                            echo '</div>'; // Cierre grid-checks-bocaditos
                        }
                        echo '</div>'; // Cierre categoria-contenido
                        echo '</div>'; // Cierre categoria-seccion
                        $index++;
                    }
                }
            }

            if (isset($categorias) && is_array($categorias) && !empty($categorias)) {
                pintarEstructuraCoctel($categorias, $t);
            } else {
                echo '<p class="text-center text-muted">' . ($t['No hay datos disponibles'] ?? 'No hay datos disponibles') . '</p>';
            }
            ?>
        </div>

        <div class="visor-columna">
            <div class="visor-sticky-card">
                <p class="visor-label"><?= $t['vista_previa'] ?? Yii::t('app', 'vista_previa') ?></p>
                <div id="contenedor-img-visor">
                    <img id="img-visor" src="<?= Yii::getAlias('@web/img/no-image.png') ?>" alt="Referencia">
                </div>
                <h5 id="nombre-bocado-visor"><?= $t['visor_instruccion'] ?? Yii::t('app', 'visor_instruccion') ?></h5>

                <div class="contador-votos">
                    <?= $t['seleccionados'] ?? Yii::t('app', 'seleccionados') ?>: <span id="count">0</span> / 6
                </div>

                <!-- Botones de control del acordeón -->
                <div class="acordeon-controls" style="margin-top: 20px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="button" onclick="expandirTodo()" class="btn-acordeon"
                        style="background: #27ae60; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;">
                        <i class="fas fa-expand"></i> <?= $t['Expandir Todo'] ?? Yii::t('app', 'Expandir Todo') ?>
                    </button>
                    <button type="button" onclick="colapsarTodo()" class="btn-acordeon"
                        style="background: #e74c3c; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;">
                        <i class="fas fa-compress"></i> <?= $t['Colapsar Todo'] ?? Yii::t('app', 'Colapsar Todo') ?>
                    </button>
                    <button type="button" onclick="expandirSeleccionados()" class="btn-acordeon"
                        style="background: #3498db; color: white; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer;">
                        <i class="fas fa-filter"></i>
                        <?= $t['Solo Seleccionados'] ?? Yii::t('app', 'Solo Seleccionados') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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
            // Usar el texto de la variable PHP
            txt.innerText = '<?= addslashes($t['visor_instruccion'] ?? Yii::t('app', 'visor_instruccion')) ?>';
        }
    }

    // ============================================
    // INICIALIZACIÓN AL CARGAR LA PÁGINA
    // ============================================

    document.addEventListener('DOMContentLoaded', function () {
        // 1. Cargar estado guardado del acordeón
        cargarEstadoAcordeon();

        // 2. Inicializar contador
        const seleccionadosInicial = document.querySelectorAll('.check-bocadito:checked').length;
        const countElement = document.getElementById('count');
        if (countElement) {
            countElement.innerText = seleccionadosInicial;
        }

        // 3. Verificar si hay checkboxes seleccionados y expandir sus secciones
        if (seleccionadosInicial > 0) {
            setTimeout(expandirSeleccionados, 500);
        }

        // 4. Asegurar que todas las secciones tengan ID si no lo tienen
        const seccionesSinId = document.querySelectorAll('.categoria-seccion:not([id])');
        seccionesSinId.forEach((seccion, index) => {
            seccion.id = 'cat-seccion-' + index;
        });

        // 5. Configurar estado inicial de checkboxes deshabilitados
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
</script>