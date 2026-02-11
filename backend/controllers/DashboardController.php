<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\modules\reservas\models\Reservas;
use yii\helpers\ArrayHelper;

class DashboardController extends Controller
{
    public function actionIndex()
    {
        // Ejemplo: Obtener sumatoria de ingresos por estado
        $statsPago = Reservas::find()
            ->select(['estado', 'SUM(total_pagado) as total'])
            ->groupBy('estado')
            ->asArray()
            ->all();

        return $this->render('index', [
            'statsPago' => $statsPago,
        ]);
    }
}