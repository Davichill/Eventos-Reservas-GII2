<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\pagos_reservas\models\PagosReservas */
?>
<div class="pagos-reservas-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_reserva',
            'monto',
            'fecha_pago',
            'metodo_pago',
            'referencia',
            'tipo_pago',
            'notas:ntext',
            'registrado_por',
        ],
    ]) ?>

</div>
