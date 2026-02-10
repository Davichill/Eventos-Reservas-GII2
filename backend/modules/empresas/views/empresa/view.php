<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\empresas\models\Empresas */
?>
<div class="empresas-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'razon_social',
            'ruc',
            'telefono',
            'email:email',
            'direccion',
            'estado',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
