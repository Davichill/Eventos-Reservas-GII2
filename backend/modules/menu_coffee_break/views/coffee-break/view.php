<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\menu_coffee_break\models\menuCoffeeBreak */
?>
<div class="menu-coffee-break-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'categoria',
            'imagen_url:url',
            'estado',
        ],
    ]) ?>

</div>
