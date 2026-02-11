<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\menu_desayuno\models\menuDesayunos */
?>
<div class="menu-desayunos-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'categoria',
            'descripcion:ntext',
            'imagen_url:url',
            'estado',
        ],
    ]) ?>

</div>
