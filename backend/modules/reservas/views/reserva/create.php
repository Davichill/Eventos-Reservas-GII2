<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\modules\reservas\models\Reservas */
/* @var $clientes array */
/* @var $tiposEvento array */
/* @var $salones array */
?>
<div class="reservas-create">
    <?= $this->render('_form', [
        'model' => $model,
        'clientes' => $clientes,     // <-- IMPORTANTE: Pasar al formulario
        'tiposEvento' => $tiposEvento, // <-- IMPORTANTE: Pasar al formulario
        'salones' => $salones,       // <-- IMPORTANTE: Pasar al formulario
    ]) ?>
</div>