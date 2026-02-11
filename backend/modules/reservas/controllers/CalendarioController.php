<?php

namespace backend\modules\reservas\controllers;

use Yii;
use yii\web\Controller;
use backend\modules\reservas\models\Reservas;

class CalendarioController extends Controller
{
    public function actionIndex()
    {
        $reservas = Reservas::find()->all();
        $eventos = [];

        foreach ($reservas as $reserva) {
            // 1. Lógica de colores (se mantiene igual)
            $color = '#001f3f';
            switch (strtoupper($reserva->estado)) {
                case 'CONFIRMADA':
                    $color = '#27ae60';
                    break;
                case 'PENDIENTE':
                    $color = '#f39c12';
                    break;
                case 'CANCELADA':
                    $color = '#e74c3c';
                    break;
                case 'COMPLETADA':
                    $color = '#3498db';
                    break;
            }

            // 2. Instanciar el objeto
            $event = new \yii2fullcalendar\models\Event();

            // 3. Propiedades oficiales (ÚNICAMENTE las que la clase permite)
            $event->id = $reserva->id;
            $event->title = $reserva->nombre_evento ?: ($reserva->tipoEvento->nombre ?? 'Reserva');
            $event->start = $reserva->fecha_evento . 'T' . $reserva->hora_inicio;
            $event->end = $reserva->fecha_evento . 'T' . $reserva->hora_fin;
            $event->color = $color;
            $event->editable = false;

            // 4. USAR NONSTANDARD PARA TODO LO EXTRA
            // No uses $event->description, ponlo aquí adentro:
            $event->nonstandard = [
                'cliente' => $reserva->cliente_nombre ?? 'N/A',
                'salon' => $reserva->salon->nombre ?? 'N/A',
                'pax' => $reserva->cantidad_personas ?? 0,
                'notas' => $reserva->observaciones ?? 'Sin observaciones',
                'description' => "Cliente: " . ($reserva->cliente_nombre ?? 'N/A') . " | Salón: " . ($reserva->salon->nombre ?? 'N/A')
            ];

            $eventos[] = $event;
        }

        return $this->render('index', [
            'eventos' => $eventos,
        ]);
    }
}