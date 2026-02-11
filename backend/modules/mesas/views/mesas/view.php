<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\mesas\models\Mesas */
?>
<div class="mesas-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nombre',
            'imagen_url:url',
        ],
    ]) ?>

</div>
