<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\modules\clientes\models\Clientes */
?>
<div class="clientes-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'id_empresa',
            'identificacion',
            'razon_social',
            'representante_legal',
            'direccion_fiscal:ntext',
            'ciudad',
            'pais',
            'correo_facturacion',
            'cliente_nombre',
            'cliente_apellido',
            'cliente_email:email',
            'cliente_telefono',
            'fecha_registro',
            'id_usuario_creador',
            'estado',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
