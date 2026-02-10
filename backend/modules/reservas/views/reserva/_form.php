<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\reservas\models\Reservas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reservas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_cliente')->textInput() ?>

    <?= $form->field($model, 'id_empresa')->textInput() ?>

    <?= $form->field($model, 'cliente_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'firma_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'firma_identificacion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contacto_evento_nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'contacto_evento_telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'id_tipo_evento')->textInput() ?>

    <?= $form->field($model, 'nombre_evento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_evento')->textInput() ?>

    <?= $form->field($model, 'hora_evento')->textInput() ?>

    <?= $form->field($model, 'cantidad_personas')->textInput() ?>

    <?= $form->field($model, 'equipos_audiovisuales')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'id_mesa')->textInput() ?>

    <?= $form->field($model, 'id_salon')->textInput() ?>

    <?= $form->field($model, 'total_evento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_pagado')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'estado_pago')->dropDownList([ 'Pendiente' => 'Pendiente', 'Parcial' => 'Parcial', 'Pagado' => 'Pagado', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'estado')->dropDownList([ 'Pendiente' => 'Pendiente', 'Confirmada' => 'Confirmada', 'Cancelada' => 'Cancelada', 'Tentativa' => 'Tentativa', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'notas')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fecha_creacion')->textInput() ?>

    <?= $form->field($model, 'hora_inicio')->textInput() ?>

    <?= $form->field($model, 'hora_fin')->textInput() ?>

    <?= $form->field($model, 'manteleria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'color_servilleta')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'logistica')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'observaciones')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'planimetria_url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'menu_opcion')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'id_coordinador')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
