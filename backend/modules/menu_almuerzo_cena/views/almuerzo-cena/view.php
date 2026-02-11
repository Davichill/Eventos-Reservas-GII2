<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\menu_almuerzo_cena\models\MenuAlmuerzoCena */
?>
<div class="menu-almuerzo-cena-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'tiempo',
            'subcategoria',
            'imagen_url:url',
            'estado',
        ],
    ]) ?>

</div>
