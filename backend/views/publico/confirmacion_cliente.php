<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\db\Query;

/* @var $this yii\web\View */
/* @var $reserva array */
/* @var $lang string */
/* @var $t array */

$t = $t ?? [];
$reserva = $reserva ?? [];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($t['confirmacion'] ?? 'Confirmación') ?> - GO Quito Hotel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        :root {
            --primary: #001f3f;
            --accent: #d4af37;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7f6;
            color: #333;
            margin: 0;
        }

        header {
            background: var(--primary);
            color: white;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .lang-switcher {
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .lang-switcher a {
            color: white;
            text-decoration: none;
            border: 1px solid white;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            margin-left: 5px;
        }

        main {
            max-width: 1000px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        section {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }

        h2 {
            color: var(--primary);
            border-left: 5px solid var(--accent);
            padding-left: 15px;
            margin-bottom: 25px;
            font-size: 1.3rem;
        }

        .seccion-flex {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .campo-flex {
            flex: 1;
            min-width: 250px;
            margin-bottom: 15px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        select,
        textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .mesa-card {
            border: 2px solid #eee;
            padding: 10px;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        .mesa-card img {
            width: 100%;
            height: 140px;
            object-fit: cover;
            border-radius: 4px;
        }

        input[type="radio"]:checked+.mesa-card {
            border-color: var(--accent);
            background: #fffcf2;
            transform: translateY(-5px);
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .mesas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
        }

        .btn-enviar {
            width: 100%;
            padding: 20px;
            background: var(--primary);
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }
    </style>
</head>

<body>

    <header>
        <div class="lang-switcher">
            <a href="<?= Url::current(['lang' => 'es']) ?>">🇪🇸 ES</a>
            <a href="<?= Url::current(['lang' => 'en']) ?>">🇺🇸 EN</a>
        </div>
        <h1><?= Html::encode($t['confirmacion'] ?? 'Confirmación') ?>:
            <?= Html::encode($reserva['nombre_evento'] ?? 'Evento') ?>
        </h1>
    </header>

    <main>
        <div style="text-align: center; margin-bottom: 30px;">
            <img src="<?= Url::to('@web/img/logo_goquito.png') ?>" alt="Logo" style="max-width: 220px;">
        </div>

        <div class="instruccion" style="background: #eef2f7; padding: 20px; border-radius: 4px; margin-bottom: 30px;">
            <p><?= $t['estimado'] ?? 'Estimado' ?>
                <strong><?= Html::encode($reserva['cliente_nombre'] ?? 'Cliente') ?></strong>,<br>
                <?= $t['instruccion'] ?? 'Complete los detalles para finalizar su reserva.' ?>
            </p>
        </div>

        <form action="<?= Url::to(['publico/finalizar']) ?>" method="POST" enctype="multipart/form-data">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
            <input type="hidden" name="token" value="<?= Html::encode($reserva['token'] ?? '') ?>">
            <input type="hidden" name="id_reserva" value="<?= Html::encode($reserva['id'] ?? '') ?>">

            <section>
                <h2>1. <?= $t['sec1_titulo'] ?? 'Datos de Facturación' ?></h2>
                <div class="seccion-flex">
                    <div class="campo-flex">
                        <label><?= $t['razon_social'] ?? 'Razón Social' ?></label>
                        <input type="text" name="razon_social" required>
                    </div>
                    <div class="campo-flex">
                        <label><?= $t['rep_legal'] ?? 'Representante Legal' ?></label>
                        <input type="text" name="representante_legal" required>
                    </div>
                </div>
                <div class="seccion-flex">
                    <div class="campo-flex">
                        <label><?= $t['id_fiscal'] ?? 'Identificación (RUC/Cédula)' ?></label>
                        <input type="text" name="identificacion" required>
                    </div>
                    <div class="campo-flex">
                        <label><?= $t['dir_fiscal'] ?? 'Dirección Fiscal' ?></label>
                        <input type="text" name="direccion_fiscal" required>
                    </div>
                </div>
                <div class="seccion-flex">
                    <div class="campo-flex">
                        <label><?= $t['tel_contacto'] ?? 'Teléfono' ?></label>
                        <input type="tel" name="telefono"
                            value="<?= Html::encode($reserva['contacto_evento_telefono'] ?? '') ?>" required>
                    </div>
                    <div class="campo-flex">
                        <label><?= $t['correo_fact'] ?? 'Correo Facturación' ?></label>
                        <input type="email" name="correo_facturacion" required>
                    </div>
                </div>
                <div
                    style="background: #fff9eb; padding: 15px; border-radius: 4px; margin-top: 10px; border: 1px solid #ffeeba;">
                    <p style="font-weight: bold; color: #856404; margin-bottom:10px;">
                        <?= $t['encargado_dia'] ?? 'Encargado el día del evento' ?>
                    </p>
                    <div class="seccion-flex">
                        <div class="campo-flex">
                            <label><?= $t['nombre_encargado'] ?? 'Nombre' ?></label>
                            <input type="text" name="contacto_evento_nombre" required>
                        </div>
                        <div class="campo-flex">
                            <label><?= $t['cel_encargado'] ?? 'Celular' ?></label>
                            <input type="tel" name="contacto_evento_telefono" required>
                        </div>
                    </div>
                </div>
            </section>

            <section>
                <h2>2. <?= $t['sec2_titulo'] ?? 'Horarios y Equipos' ?></h2>
                <div class="seccion-flex">
                    <div class="campo-flex">
                        <label><?= $t['h_inicio'] ?? 'Hora Inicio' ?></label>
                        <select name="hora_inicio" id="hora_inicio" required></select>
                    </div>
                    <div class="campo-flex">
                        <label><?= $t['h_fin'] ?? 'Hora Fin' ?></label>
                        <select name="hora_fin" id="hora_fin" required disabled></select>
                    </div>
                </div>
                <div class="campo-flex" style="margin-top:15px;">
                    <label><?= $t['equipos'] ?? 'Equipos Audiovisuales Requeridos' ?></label>
                    <input type="text" name="equipos_audiovisuales" placeholder="Ej: Proyector, micrófonos...">
                </div>
            </section>

            <section>
                <h2>3. <?= $t['sec3_titulo'] ?? 'Selección de Menú' ?></h2>
                <div style="background: #f9f9f9; padding: 20px; border-radius: 8px;">
                    <?php
                    $id_tipo = (int) ($reserva['id_tipo_evento'] ?? 0);
                    $mapa_vistas = [1 => 'desayunos.php', 2 => 'seminario.php', 3 => 'coctel.php', 5 => 'almuerzo_cena.php', 6 => 'coffee_break.php'];
                    $archivo = $mapa_vistas[$id_tipo] ?? null;

                    if ($archivo && file_exists(__DIR__ . '/vistas_menu/' . $archivo)) {
                        echo $this->render('vistas_menu/' . $archivo, ['t' => $t, 'reserva' => $reserva]);
                    } else {
                        echo "<p>" . ($t['coordine_asesor'] ?? 'Coordine detalles con su asesor.') . "</p>";
                    }
                    ?>
                </div>
            </section>

            <section>
                <h2>4. <?= $t['sec4_titulo'] ?? 'Tipo de Montaje' ?></h2>
                <div class="mesas-grid">
                    <?php
                    // Consultamos todas las mesas de la DB
                    $mesasDb = (new Query())->select(['id', 'nombre', 'imagen'])->from('mesas')->all();
                    foreach ($mesasDb as $m): ?>
                        <label style="margin:0;">
                            <input type="radio" name="id_mesa" value="<?= $m['id']; ?>" style="display:none;" required>
                            <div class="mesa-card">
                                <?php if (!empty($m['imagen'])): ?>
                                    <img src="data:image/jpeg;base64,<?= base64_encode($m['imagen']); ?>" alt="Setup">
                                <?php else: ?>
                                    <img src="<?= Url::to('@web/img/no-image.png') ?>" alt="Sin imagen">
                                <?php endif; ?>
                                <p style="margin-top:10px;"><strong><?= Html::encode($m['nombre']); ?></strong></p>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="seccion-flex" style="margin-top:25px;">
                    <div class="campo-flex">
                        <label><?= $t['manteleria'] ?? 'Color de Mantelería' ?></label>
                        <select name="manteleria" required>
                            <option value="Blanco">Blanco / White</option>
                            <option value="Negro">Negro / Black</option>
                            <option value="Champagne">Champagne</option>
                        </select>
                    </div>
                    <div class="campo-flex">
                        <label><?= $t['servilletas'] ?? 'Color de Servilletas' ?></label>
                        <select name="color_servilleta" required>
                            <option value="Blanco">Blanco / White</option>
                            <option value="Rojo">Rojo / Red</option>
                        </select>
                    </div>
                </div>
            </section>

            <section>
                <h2>5. <?= $t['sec5_titulo'] ?? 'Información Adicional' ?></h2>
                <div class="campo-flex">
                    <label><?= $t['obs_cocina'] ?? 'Observaciones de Cocina (Alergias, etc)' ?></label>
                    <textarea name="observaciones" rows="3"></textarea>
                </div>
                <div class="campo-flex">
                    <label><?= $t['logistica'] ?? 'Logística de Montaje / Otros' ?></label>
                    <textarea name="logistica" rows="3"></textarea>
                </div>
                <div class="campo-flex"
                    style="border: 2px dashed #ccc; padding: 20px; text-align: center; background: #fafafa;">
                    <label><?= $t['planimetria'] ?? 'Subir Planimetría o Referencia' ?></label>
                    <input type="file" name="planimetria" accept="image/*,application/pdf">
                </div>
            </section>

            <button type="submit" class="btn-enviar">
                <?= $t['btn_enviar'] ?? 'Confirmar Reserva' ?>
            </button>
        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startSelect = document.getElementById('hora_inicio');
            const endSelect = document.getElementById('hora_fin');
            const isEs = "<?php echo $lang; ?>" === 'es';

            // Mantiene el desplegable con tamaño fijo de 8 para que no ocupe toda la pantalla
            const makeCompact = (el) => {
                el.addEventListener('mousedown', function () {
                    if (this.options.length > 8) this.size = 8;
                });
                const reset = function () { this.size = 0; };
                el.addEventListener('change', reset);
                el.addEventListener('blur', reset);
            };

            makeCompact(startSelect);
            makeCompact(endSelect);

            function format12h(minutes) {
                let h = Math.floor(minutes / 60) % 24;
                let m = minutes % 60;
                let ampm = h >= 12 ? 'PM' : 'AM';
                let displayH = h % 12 || 12;
                let displayM = m === 0 ? '00' : m;
                return `${displayH}:${displayM} ${ampm}`;
            }

            function format24h(minutes) {
                let h = Math.floor(minutes / 60) % 24;
                let m = minutes % 60;
                return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}:00`;
            }

            for (let i = 0; i < 1440; i += 30) {
                let opt = document.createElement('option');
                opt.value = format24h(i);
                opt.textContent = format12h(i);
                startSelect.appendChild(opt);
            }

            startSelect.addEventListener('change', function () {
                const [h, m] = this.value.split(':').map(Number);
                const startMins = (h * 60) + m;
                endSelect.innerHTML = '';
                endSelect.disabled = false;

                for (let i = 30; i <= 1440; i += 30) {
                    let currentMins = startMins + i;
                    if (currentMins > 1440) break;

                    let opt = document.createElement('option');
                    opt.value = format24h(currentMins);

                    let totalHoras = i / 60;
                    let duracionTexto = "";

                    // Solo muestra texto de horas (1 hora, 2 horas...) si es exacto
                    if (Number.isInteger(totalHoras)) {
                        let unit = isEs ? (totalHoras === 1 ? 'hora' : 'horas') : (totalHoras === 1 ? 'hour' : 'hours');
                        duracionTexto = ` (${totalHoras} ${unit})`;
                    }

                    opt.textContent = `${format12h(currentMins)}${duracionTexto}`;
                    endSelect.appendChild(opt);
                }
            });
        });
    </script>
</body>

</html>