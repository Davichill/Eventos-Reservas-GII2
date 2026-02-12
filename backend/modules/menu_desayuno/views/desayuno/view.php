<?php

use yii\widgets\DetailView;
use yii\helpers\Html;
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
            [
                'attribute' => 'imagen',
                'format' => 'raw', // Importante para renderizar el HTML de la imagen
                'value' => function($model) {
                    if ($model->imagen) {
                        // Convertimos el binario a Base64
                        $imageData = base64_encode($model->imagen);
                        $src = 'data:image/jpeg;base64,' . $imageData;
                        return Html::img($src, [
                            'class' => 'img-thumbnail',
                            'style' => 'max-width:300px; max-height:300px;' // Tamaño para la vista de detalle
                        ]);
                    }
                    return '<span class="text-danger">Sin imagen disponible</span>';
                },
            ],
        ],
    ]) ?>

</div>
