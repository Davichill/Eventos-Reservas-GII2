<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\menu_coctel\models\menuCoctel */
?>
<div class="menu-coctel-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'categoria',
            'subcategoria',
            'imagen_url:url',
            'estado',
        ],
    ]) ?>

</div>
