<?php
use yii\helpers\Html;
use backend\modules\reservas\models\TiposEvento;
use backend\modules\reservas\models\Salones;

// Preparar el Logo (Ruta absoluta para que el generador de PDF no falle)
$logoPath = Yii::getAlias('@webroot/img/logo.png'); // Ajusta la extensión .png o .jpg


/** * Lógica para obtener el nombre de la empresa 
 * Se relaciona: Reserva -> Cliente -> Empresa
 */
$nombreEmpresa = "";
if (isset($model->cliente->empresa)) {
    $nombreEmpresa = $model->cliente->empresa->razon_social;
} elseif (isset($model->empresas)) { // Por si acaso existe relación directa
    $nombreEmpresa = $model->empresas->razon_social;
}
?>
<div style="font-family: Arial, sans-serif;">

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
        <tr>
            <td style="width: 30%; vertical-align: middle;">
                <img src="<?= $logoPath ?>" style="width: 150px; height: auto;">
            </td>
            <td style="width: 70%; text-align: right; vertical-align: middle;">

                <div style="font-size: 10px; color: #555; text-align: center;">https://goquitohotel.com</div>
            </td>
        </tr>
    </table>

    <div style="font-size: 18px; font-weight: bold; color: #002D5E; margin-top: 10px;">
        # <?= Html::encode($model->nombre_evento ?? '') ?>
    </div>
    <div style="font-size: 28px; font-weight: bold; color: #002D5E; margin: 5px 0 15px;">PROPUESTA</div>

    <table style="width: 100%; font-size: 11px; border-collapse: collapse; margin-bottom: 15px;">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <strong>Empresa:</strong>
                <?= Html::encode($nombreEmpresa) ?><br>
                <strong>Contacto:</strong>
                <?= Html::encode($model->contacto_evento_nombre ?? $model->cliente->cliente_nombre ?? '') ?><br>
                <strong>Correo electrónico:</strong> <?= Html::encode($model->cliente->cliente_email ?? '') ?><br>
                <strong>Teléfono:</strong>
                <?= Html::encode($model->contacto_evento_telefono ?? $model->cliente->cliente_telefono ?? '') ?>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <strong>Evento:</strong> <?= Html::encode($model->nombre_evento ?? '') ?><br>
                <strong>Agente:</strong> <?= Html::encode($model->coordinador->nombre_completo ?? '') ?><br>
                <strong>Correo electrónico:</strong> eventos@goquitohotel.com<br>
                <strong>Teléfono:</strong> +593 99 909 9004 ext. marke
            </td>
        </tr>
    </table>

    <p style="font-size: 11px; text-align: justify;">
        Buen dia <?= Html::encode($model->contacto_evento_nombre ?? $model->cliente->cliente_nombre ?? '') ?>.<br>
        GO Quito Hotel, reconocido como South America's Leading New Hotel 2025 por los World Travel Awards,
        agradece su interés para la realización del evento <?= Html::encode($model->nombre_evento ?? '') ?>.
        A continuación, encontrará los detalles propuestos y costos asociados para las fechas solicitadas.
        Si desea confirmar mediante un contrato, por favor háganoslo saber lo antes posible.
    </p>

    <p style="font-size: 11px; text-align: justify; background: #f4f7f9; padding: 10px; border-radius: 5px;">
        <strong>Condiciones de tarifa:</strong> Vigencia de 48h. Requiere abono del 50% para garantizar disponibilidad;
        saldo restante 7 días antes del evento. Gastos adicionales se liquidan al finalizar.
        En caso de incumplimientos o cambios no gestionados, el HOTEL podrá revisar condiciones del contrato.
        Clientes sin crédito corporativo deben dejar garantía (signature on file), reembolsable si no hay saldos
        pendientes.
    </p>

    <h4 style="margin-top: 25px; color: #002D5E; border-bottom: 1px solid #ccc; padding-bottom: 5px;">PROGRAMA DE EVENTO
    </h4>
    <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
        <thead>
            <tr style="background: #002D5E; color: white;">
                <th style="padding: 8px; border: 1px solid #002D5E;">Name</th>
                <th style="padding: 8px; border: 1px solid #002D5E;">DateTime</th>
                <th style="padding: 8px; border: 1px solid #002D5E;">Area</th>
                <th style="padding: 8px; border: 1px solid #002D5E;">Event Type</th>
                <th style="padding: 8px; border: 1px solid #002D5E;">Guests</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 8px; border: 1px solid #eee;"><?= Html::encode($model->nombre_evento ?? '') ?></td>
                <td style="padding: 8px; border: 1px solid #eee;">
                    <?php
                    $fecha = !empty($model->fecha_evento) ? date('d/m/Y', strtotime($model->fecha_evento)) : '';
                    $horaInicio = $model->hora_inicio ?? '';
                    $horaFin = $model->hora_fin ?? '';
                    echo trim($fecha . ' ' . $horaInicio . ($horaInicio && $horaFin ? ' – ' : '') . $horaFin);
                    ?>
                </td>
                <td style="padding: 8px; border: 1px solid #eee;">
                    <?php
                    $nombreSalon = '';
                    if (isset($model->salon) && is_object($model->salon)) {
                        $nombreSalon = $model->salon->nombre_salon ?? '';
                    } elseif (isset($model->id_salon)) {
                        $salonModel = Salones::findOne($model->id_salon);
                        if ($salonModel) {
                            $nombreSalon = $salonModel->nombre_salon;
                        }
                    }
                    echo Html::encode($nombreSalon);
                    ?>
                </td>
                <td style="padding: 8px; border: 1px solid #eee;">
                    <?php
                    $nombreTipoEvento = '';
                    if (isset($model->tipoEvento) && is_object($model->tipoEvento)) {
                        $nombreTipoEvento = $model->tipoEvento->nombre ?? '';
                    } elseif (isset($model->id_tipo_evento)) {
                        $tipoEventoModel = TiposEvento::findOne($model->id_tipo_evento);
                        if ($tipoEventoModel) {
                            $nombreTipoEvento = $tipoEventoModel->nombre;
                        }
                    }
                    echo Html::encode($nombreTipoEvento);
                    ?>
                </td>
                <td style="padding: 8px; border: 1px solid #eee; text-align: center;">
                    <?= $model->cantidad_personas ?? '' ?>
                </td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px;">
    <h4 style="color: #002D5E; border-bottom: 1px solid #ccc;">MENÚ SELECCIONADO</h4>
    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 8px; border: 0.1mm solid #ddd; text-align: left;">Plato</th>
                <th style="padding: 8px; border: 0.1mm solid #ddd; text-align: left;">Categoría</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($model->detallesMenu)): ?>
                <?php foreach ($model->detallesMenu as $plato): ?>
                    <tr>
                        <td style="padding: 8px; border: 0.1mm solid #eee;">
                            <?= \yii\helpers\Html::encode($plato->nombre_plato) ?>
                        </td>
                        <td style="padding: 8px; border: 0.1mm solid #eee;">
                            <?= \yii\helpers\Html::encode($plato->categoria ?? 'General') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" style="padding: 10px; text-align: center; border: 0.1mm solid #eee;">
                        No se han seleccionado platos para esta reserva.
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

    <div style="margin: 20px 0; text-align: center;">
        <img src="https://via.placeholder.com/800x100?text=IMAGEN+REFERENCIAL"
            style="max-width: 100%; height: auto; border: 1px solid #ccc;">
    </div>

    <h4 style="margin-top: 15px; color: #002D5E; border-bottom: 1px solid #ccc; padding-bottom: 5px;">BEVERAGE</h4>
    <p style="font-size: 11px;"><?= Html::encode($model->bebidas_descripcion ?? '') ?></p>

</div>

<h4 style="margin-top: 25px; color: #002D5E; border-bottom: 1px solid #ccc; padding-bottom: 5px;">EQUIPOS AUDIOVISUALES</h4>
<table style="width: 100%; border-collapse: collapse; font-size: 11px; border: 0.1mm solid #ddd;"> <thead>
        <tr style="background: #f2f2f2;">
            <th style="padding: 10px; border: 0.1mm solid #ddd; text-align: left;">Descripción</th>
            <th style="padding: 10px; border: 0.1mm solid #ddd; text-align: center;">Cantidad</th>
            <th style="padding: 10px; border: 0.1mm solid #ddd; text-align: right;">Precio</th>
            <th style="padding: 10px; border: 0.1mm solid #ddd; text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($model->equipos_audiovisuales)): ?>
            <?php
            $equipos = explode("\n", $model->equipos_audiovisuales);
            foreach ($equipos as $equipo):
                if (trim($equipo)): ?>
                    <tr>
                        <td style="padding: 10px; border: 0.1mm solid #eee;"><?= Html::encode(trim($equipo)) ?></td>
                        <td style="padding: 10px; border: 0.1mm solid #eee; text-align: center;">1</td>
                        <td style="padding: 10px; border: 0.1mm solid #eee; text-align: right;">0.00</td> <td style="padding: 10px; border: 0.1mm solid #eee; text-align: right;">0.00</td>
                    </tr>
                <?php endif;
            endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" style="padding: 10px; border: 0.1mm solid #eee; text-align: center;">No hay equipos registrados</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<h4 style="margin-top: 25px; color: #002D5E; border-bottom: 1px solid #ccc; padding-bottom: 5px;">ADDITIONAL CHARGES
</h4>
<table style="width: 100%; border-collapse: collapse; font-size: 11px;">
    <thead>
        <tr style="background: #f2f2f2;">
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Descripción</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: center;">Qty</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Price</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Total</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($model->manteleria)): ?>
            <tr>
                <td style="padding: 10px; border: 1px solid #eee;">MANTEL/LYCRA <?= Html::encode($model->manteleria) ?></td>
                <td style="padding: 10px; border: 1px solid #eee; text-align: center;">1</td>
                <td style="padding: 10px; border: 1px solid #eee; text-align: right;"></td>
                <td style="padding: 10px; border: 1px solid #eee; text-align: right;"></td>
            </tr>
        <?php endif; ?>

        <?php if (!empty($model->color_servilleta)): ?>
            <tr>
                <td style="padding: 10px; border: 1px solid #eee;">Servilleta <?= Html::encode($model->color_servilleta) ?>
                    - adornos bajos</td>
                <td style="padding: 10px; border: 1px solid #eee; text-align: center;">1</td>
                <td style="padding: 10px; border: 1px solid #eee; text-align: right;"></td>
                <td style="padding: 10px; border: 1px solid #eee; text-align: right;"></td>
            </tr>
        <?php endif; ?>

        <?php if (empty($model->manteleria) && empty($model->color_servilleta)): ?>
            <tr>
                <td colspan="4" style="padding: 10px; border: 1px solid #eee; text-align: center;">No hay cargos adicionales
                    registrados</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<h4 style="margin-top: 25px; color: #002D5E; border-bottom: 1px solid #ccc; padding-bottom: 5px;">BILLING INFORMATION
</h4>
<table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 10px;">
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Alimentos / Food</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;">
            <?= isset($model->total_evento) ? '$ ' . number_format($model->total_evento, 2) : '' ?>
        </td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Total Audiovisual</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;"></td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Subtotal</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;"></td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Cargo por electricidad / Electricity fee</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;"></td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Cargo por catering / Catering fee</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;"></td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Cargo por recolección de residuos / Trash fee</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;"></td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Servicio / Service charge 25.0%</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;"></td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">IVA / VAT 10.0%</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;"></td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Alquiler de espacio / Venue rental 15.0%</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;"></td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Habitaciones cargadas - a la cuenta / Guest rooms
            Charged - to master account</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;"></td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Consumo mínimo de alimentos y bebidas / F&B minimum
        </td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;"></td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Total</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;"></td>
    </tr>
    <tr>
        <td style="padding: 8px; border-bottom: 1px solid #eee;">Met</td>
        <td style="text-align: right; padding: 8px; border-bottom: 1px solid #eee;">-$0.00</td>
    </tr>
    <tr style="font-size: 18px; font-weight: bold; color: #002D5E;">
        <td style="padding: 15px 8px;">Total / Grand total</td>
        <td style="text-align: right; padding: 15px 8px;">
            <?= isset($model->total_evento) ? '$ ' . number_format($model->total_evento, 2) : '' ?>
        </td>
    </tr>
</table>

<h4 style="margin-top: 25px; color: #002D5E; border-bottom: 1px solid #ccc; padding-bottom: 5px;">PROPOSAL DETAILS</h4>
<div style="margin-top: 15px; padding: 15px; background: #f9f9f9; border-radius: 5px; font-size: 10px;">

    <p><strong>Catering:</strong> nuestro servicio fuera del Hotel combina logística eficiente, cocina creativa y
        atención profesional. Más información aquí.</p>

    <p><strong>Alojamiento:</strong> 151 habitaciones de lujo (desde 39 m²), con vista panorámica de Quito, ideales para
        relajarse y disfrutar de la belleza de la ciudad. El hotel cuenta con galería, piscina, spa, gimnasio, cafetería
        y dos restaurantes. Más información aquí.</p>

    <p><strong>Spa:</strong> un refugio de bienestar creado para consentir cada uno de los sentidos; reconectar cuerpo y
        mente con aromas naturales, manos expertas y tratamientos inspirados en la cultura local. Más información aquí.
    </p>

    <?php if (!empty($model->observaciones)): ?>
        <p><strong>Observaciones adicionales:</strong> <?= Html::encode($model->observaciones) ?></p>
    <?php endif; ?>

    <?php if (!empty($model->logistica)): ?>
        <p><strong>Logística:</strong> <?= Html::encode($model->logistica) ?></p>
    <?php endif; ?>
</div>

<h4 style="margin-top: 25px; color: #002D5E; border-bottom: 1px solid #ccc; padding-bottom: 5px;">OPTIONS</h4>
<div
    style="margin-top: 15px; padding: 15px; border: 1px solid #eee; border-radius: 5px; font-size: 10px; min-height: 50px;">
</div>

<div
    style="margin-top: 30px; padding: 15px; border: 1px solid #eee; border-radius: 5px; font-size: 9px; color: #555; text-align: justify;">
    <p><strong>Términos y Condiciones:</strong></p>
    <p>1. Esta cotización tiene una vigencia de 48 horas a partir de su emisión.</p>
    <p>2. Para confirmar el evento, se requiere un abono del 50% del valor total presupuestado.</p>
    <p>3. Cambios en el número de pax (personas) se aceptarán hasta 72 horas antes del evento.</p>
    <p>4. No se permite el ingreso de alimentos o bebidas ajenas al establecimiento sin previa autorización.</p>
</div>

<div style="margin-top: 40px; text-align: center; font-size: 11px;">
    <table style="width: 100%;">
        <tr>
            <td style="width: 50%; padding-top: 40px;">
                __________________________<br>
                Firma del Cliente
            </td>
            <td style="width: 50%; padding-top: 40px;">
                __________________________<br>
                Firma Autorizada Hotel
            </td>
        </tr>
    </table>
</div>