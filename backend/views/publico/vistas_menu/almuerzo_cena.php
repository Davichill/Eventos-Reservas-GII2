<?php
use yii\helpers\Html;
use yii\db\Query;
use yii\db\Expression;

// FUNCIÓN PINTAR CHECK: Adaptada para LONGBLOB y compatible con tu JS
if (!function_exists('pintarCheckCena')) {
    function pintarCheckCena($item, $grupo, $t, $tiempo_db, $info_extra = [])
    {
        // Convertimos el LONGBLOB a Base64
        $ruta_img = !empty($item['imagen']) 
            ? 'data:image/jpeg;base64,' . base64_encode($item['imagen']) 
            : "img/no-image.png";

        $nombre = $item['nombre'];
        $nombreTraducido = isset($t[$nombre]) ? $t[$nombre] : $nombre;

        // Construcción de atributos para que tu JS los lea correctamente
        $guarnicion = htmlspecialchars($info_extra['guarnicion'] ?? '', ENT_QUOTES);
        $vegetales = htmlspecialchars($info_extra['vegetales'] ?? '', ENT_QUOTES);

        echo "<label class='item-cena' 
                data-img='$ruta_img' 
                data-nombre='$nombreTraducido' 
                data-tiempo='$tiempo_db' 
                data-guarnicion='$guarnicion' 
                data-vegetales='$vegetales'>
                <input type='checkbox' name='bocaditos[]' value='$nombre' data-group='$grupo'> 
                <span>$nombreTraducido</span>
              </label>";
    }
}

$lang = (Yii::$app->language == 'en') ? 'en' : 'es';
// Asumiendo que $texts viene de tu configuración de idiomas
$t_menu = $t; 
?>

<link rel="stylesheet" href="<?= \yii\helpers\Url::to('@web/css/menu/almuerzo_cena.css') ?>">

<div class="instruccion" style="grid-column: 1 / -1; background: #fff8e1; border-left: 5px solid #ffc107; padding: 15px; margin-bottom: 20px;">
    <strong><?php echo $t_menu['menu_almuerzo_title'] ?? 'Menú'; ?></strong> <?php echo $t_menu['menu_almuerzo_desc'] ?? ''; ?>
</div>

<div style="grid-column: 1 / -1; display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap;">
    <label class="plan-card">
        <input type="radio" name="menu_opcion" value="Menú 2 Tiempos" onclick="activarMenu(2)" required>
        <div class="plan-titulo"><?php echo $t_menu['menu_2_tiempos'] ?? '2 Tiempos'; ?></div>
        <ul class="plan-detalles"><li><?php echo $t_menu['menu_2_sub'] ?? ''; ?></li></ul>
    </label>

    <label class="plan-card">
        <input type="radio" name="menu_opcion" value="Menú 3 Tiempos" onclick="activarMenu(3)">
        <div class="plan-titulo"><?php echo $t_menu['menu_3_tiempos'] ?? '3 Tiempos'; ?></div>
        <ul class="plan-detalles"><li><?php echo $t_menu['menu_3_sub'] ?? ''; ?></li></ul>
    </label>

    <label class="plan-card">
        <input type="radio" name="menu_opcion" value="Menú 4 Tiempos" onclick="activarMenu(4)">
        <div class="plan-titulo">Menú 4 Tiempos</div>
        <ul class="plan-detalles"><li>Entrada + Plato Fuerte + Postre + Sorbet</li></ul>
    </label>
</div>

<div id="contenedor-menu-cena" style="grid-column: 1 / -1; opacity: 0.5; pointer-events: none; transition: 0.3s; display: grid; grid-template-columns: 1fr 320px; gap: 20px;">

    <div class="opciones-cena">
        <?php
        $secciones_config = [
            'Entradas' => ['titulo' => $t_menu['entradas_title'] ?? 'Entradas', 'grupo' => 'Entradas'],
            'Plato Fuerte' => ['titulo' => $t_menu['platos_fuertes_title'] ?? 'Plato Fuerte', 'grupo' => 'Plato Fuerte'],
            'Postres' => ['titulo' => $t_menu['postres_title'] ?? 'Postres', 'grupo' => 'Postres']
        ];

        foreach ($secciones_config as $seccion_db => $info):
            ?>
            <details class="seccion-seminario">
                <summary><?php echo $info['titulo']; ?></summary>
                <div class="contenido-seminario">
                    <?php
                    // CORRECCIÓN DISTINCT PARA YII2
                    $subcategorias = (new Query())
                        ->select(['subcategoria'])
                        ->distinct()
                        ->from('menu_almuerzo_cena')
                        ->where(['tiempo' => $seccion_db, 'estado' => 1])
                        ->orderBy(['subcategoria' => SORT_ASC])
                        ->all();

                    if (!empty($subcategorias)):
                        foreach ($subcategorias as $sub):
                            $nombreSub = $sub['subcategoria'];
                            $nombreSubTraducido = isset($t_menu[$nombreSub]) ? $t_menu[$nombreSub] : $nombreSub;
                            ?>
                            <p class="sub-cat"><?php echo $nombreSubTraducido; ?></p>
                            <div class="grid-items">
                                <?php
                                $platos = (new Query())
                                    ->from('menu_almuerzo_cena')
                                    ->where(['tiempo' => $seccion_db, 'subcategoria' => $nombreSub, 'estado' => 1])
                                    ->all();

                                foreach ($platos as $item):
                                    $info_extra = [];
                                    if ($seccion_db == 'Plato Fuerte') {
                                        // Aquí puedes asignar tus campos de guarnición si existen en la tabla
                                        $info_extra = [
                                            'guarnicion' => $item['guarnicion'] ?? '',
                                            'vegetales' => $item['vegetales'] ?? ''
                                        ];
                                    }

                                    pintarCheckCena($item, $info['grupo'], $t_menu, $seccion_db, $info_extra);
                                endforeach;
                                ?>
                            </div>
                            <?php
                        endforeach;
                    else:
                        echo "<small style='color:#999; padding:10px; display:block;'>No options available.</small>";
                    endif;
                    ?>
                </div>
            </details>
        <?php endforeach; ?>
    </div>

    <div class="visor-cena" style="padding-top: 30px;">
        <div style="position: sticky; top: 20px; background: #fff; border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center;">
            <p style="font-size: 11px; font-weight: bold; color: #888; margin-bottom: 10px; text-transform: uppercase;">
                <?php echo $t_menu['vista_previa'] ?? 'VISTA PREVIA'; ?>
            </p>

            <div style="width: 100%; height: 200px; overflow: hidden; border-radius: 4px; background: #f9f9f9; border: 1px solid #eee;">
                <img id="preview-image" src="img/no-image.png" style="width: 100%; height: 100%; object-fit: cover; transition: 0.3s;">
            </div>

            <p id="preview-title" style="margin-top: 15px; font-weight: bold; font-size: 14px; color: #333; min-height: 40px;">
                <?php echo $t_menu['select_plate'] ?? 'Seleccione un plato'; ?>
            </p>

            <div id="preview-details" style="display: none; margin-top: 10px; text-align: left; font-size: 0.85rem;">
                <div style="margin-bottom: 5px;">
                    <strong><?php echo $t_menu['guarnicion'] ?? 'Guarnición'; ?>:</strong>
                    <span id="detail-guarnicion" style="color: #555;">-</span>
                </div>
                <div>
                    <strong><?php echo $t_menu['vegetales'] ?? 'Vegetales'; ?>:</strong>
                    <span id="detail-vegetales" style="color: #555;">-</span>
                </div>
            </div>

            <div class="selection-summary" style="margin-top: 15px; padding: 10px; background: #f8f9fa; border-radius: 6px;">
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem;">
                    <strong><?php echo $t_menu['total_selected'] ?? 'Seleccionados'; ?>:</strong>
                    <div>
                        <span id="selected-count" style="font-weight: 600; color: #d35400;">0</span> /
                        <span id="max-selections" style="font-weight: 600;">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/menu/almuerzo_cena.js"></script>