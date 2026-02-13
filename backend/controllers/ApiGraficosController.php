<?php

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\db\Query;
use yii\db\Expression;

class ApiGraficosController extends Controller
{
    public function init()
    {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public function actionIndex()
    {
        Yii::$app->response->format = Response::FORMAT_HTML;
        return $this->render('/dashboard/index');
    }

    //Ocupacion de eventos por dia
    public function actionEventosPorDia($dias = 7)
    {
        $fechaFin = date('Y-m-d', strtotime("+$dias days"));
        $fechaInicio = date('Y-m-d');

        // 1. Obtener datos reales de la DB
        $raw = (new Query())
            ->select(['fecha' => 'fecha_evento', 'cantidad' => 'COUNT(*)'])
            ->from('reservas')
            ->where(['between', 'fecha_evento', $fechaInicio, $fechaFin])
            ->groupBy('fecha_evento')
            ->all();

        // 2. Mapear para que los días sin eventos salgan con 0 (Vital para el efecto visual)
        $map = array_column($raw, 'cantidad', 'fecha');
        $labels = [];
        $values = [];

        for ($i = 0; $i < $dias; $i++) {
            $fechaLoop = date('Y-m-d', strtotime("+$i days"));
            $labels[] = date('d/m', strtotime($fechaLoop)); // Formato día/mes
            $values[] = isset($map[$fechaLoop]) ? (int) $map[$fechaLoop] : 0;
        }

        return [
            'success' => true,
            'data' => ['labels' => $labels, 'values' => $values]
        ];
    }

    //Estado de cobros totales
    public function actionEstadoCobros($filtro = 'todos')
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $queryRes = (new \yii\db\Query())->from('reservas');
        $queryPag = (new \yii\db\Query())->from('pagos_reservas');

        if ($filtro !== 'todos') {
            $d = ($filtro == '7') ? 7 : 30;
            $fechaLimit = date('Y-m-d', strtotime("-$d days"));
            $queryRes->where(['>=', 'fecha_evento', $fechaLimit]);
            $queryPag->where(['>=', 'fecha_pago', $fechaLimit]);
        }

        $proyectado = (float) $queryRes->sum('total_evento') ?: 0;
        $pagado = (float) $queryPag->sum('monto') ?: 0;
        $pendiente = max(0, $proyectado - $pagado);

        return [
            'success' => true,
            'data' => [
                'proyectado' => $proyectado,
                'pagado' => $pagado,
                'pendiente' => $pendiente,
                'p_pagado' => $proyectado > 0 ? round(($pagado / $proyectado) * 100) : 0,
                'p_pend' => $proyectado > 0 ? round(($pendiente / $proyectado) * 100) : 0,
            ]
        ];
    }


    //Ingresos promerdio por tipo de Salones
    public function actionIngresosPorTipo($filtro = 'todos')
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Obtener el total general para calcular porcentajes
        $totalGeneral = (new \yii\db\Query())
            ->from('reservas')
            ->sum('total_evento') ?: 0;

        // Consultar ingresos por cada tipo definido en la tabla tipos_evento
        $datos = (new \yii\db\Query())
            ->select([
                't.nombre AS tipo',
                'SUM(r.total_evento) AS total',
            ])
            ->from('tipos_evento t')
            ->leftJoin('reservas r', 'r.id_tipo_evento = t.id')
            ->groupBy(['t.id', 't.nombre'])
            ->all();

        $labels = [];
        $values = [];
        $detalles = [];

        foreach ($datos as $row) {
            $monto = (float) $row['total'];
            $porcentaje = $totalGeneral > 0 ? round(($monto / $totalGeneral) * 100) : 0;

            $labels[] = $row['tipo'];
            $values[] = $monto;
            $detalles[] = [
                'nombre' => $row['tipo'],
                'monto' => number_format($monto, 2, ',', '.') . ' US$',
                'porcentaje' => $porcentaje . '% del total'
            ];
        }

        return [
            'success' => true,
            'labels' => $labels,
            'values' => $values,
            'detalles' => $detalles
        ];
    }

    //Calendario Pequeño

    public function actionEventosCalendario()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Agrupamos por fecha y estado para contar los eventos
        $resultados = (new \yii\db\Query())
            ->select([
                'fecha_evento',
                'estado',
                'COUNT(*) as total'
            ])
            ->from('reservas')
            ->groupBy(['fecha_evento', 'estado'])
            ->all();

        $eventos = [];
        foreach ($resultados as $res) {
            // Definimos el prefijo y color según el enum del estado
            $prefijo = substr($res['estado'], 0, 1) . ': '; // P: o C:
            $color = '#f1c40f'; // Por defecto amarillo (Pendiente)

            if ($res['estado'] == 'Confirmada')
                $color = '#2ecc71'; // Verde
            if ($res['estado'] == 'Cancelada')
                $color = '#e74c3c'; // Rojo
            if ($res['estado'] == 'Completada')
                $color = '#3498db'; // Azul

            $eventos[] = [
                'title' => $prefijo . $res['total'],
                'start' => $res['fecha_evento'],
                'backgroundColor' => $color,
                'borderColor' => $color,
                'allDay' => true
            ];
        }

        return $eventos;
    }
}