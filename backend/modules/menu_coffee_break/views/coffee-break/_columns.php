<?php
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
        // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'id',
    // ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'nombre',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'categoria',
    ],
    [
        'class' => '\kartik\grid\DataColumn',
        'attribute' => 'imagen', // Asegúrate de usar el nombre de la columna BLOB
        'label' => 'Vista Previa',
        'format' => 'raw',
        'value' => function($model) {
            if ($model->imagen) {
                // Convertimos el binario a Base64 para mostrarlo en el tag img
                $imageData = base64_encode($model->imagen);
                $src = 'data:image/jpeg;base64,' . $imageData;
                return \yii\helpers\Html::img($src, [
                    'style' => 'width:80px; height:auto; border-radius:5px; shadow: 2px 2px 5px #ccc;'
                ]);
            }
            return '<span class="label label-default">Sin imagen</span>';
        },
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'dropdown' => false,
        'vAlign'=>'middle',
        'urlCreator' => function($action, $model, $key, $index) { 
                return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','data-toggle'=>'tooltip'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Update', 'data-toggle'=>'tooltip'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Delete', 
                          'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                          'data-request-method'=>'post',
                          'data-toggle'=>'tooltip',
                          'data-confirm-title'=>'Are you sure?',
                          'data-confirm-message'=>'Are you sure want to delete this item'], 
    ],

];   