<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\menu_seminario\models\menuSeminario */
?>
<div class="menu-seminario-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'seccion',
            'categoria',
            'imagen_url:url',
            'estado',
        ],
    ]) ?>

</div>
