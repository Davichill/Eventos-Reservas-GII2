<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


$esNuevo = $model->isNewRecord;

// Si por alguna razón el controlador no pasó las listas, las cargamos aquí como respaldo
if (!isset($clientes)) {
    $clientes = ArrayHelper::map(\backend\modules\clientes\models\Clientes::find()->all(), 'id', 'cliente_nombre');
}
if (!isset($tiposEvento)) {
    $tiposEvento = ArrayHelper::map(\backend\modules\reservas\models\TiposEvento::find()->all(), 'id', 'nombre');
}
if (!isset($salones)) {
    $salones = ArrayHelper::map(\backend\modules\reservas\models\Salones::find()->all(), 'id', 'nombre_salon');
}
?>


<div class="reservas-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'id_cliente')->dropDownList($clientes, [
                'prompt' => '--- Seleccione Cliente ---',
                'class' => 'form-control select2' // Si usas select2 para buscar mejor
            ]) ?>
        </div>
        
        <div class="col-md-6">
            <?= $form->field($model, 'nombre_evento')->textInput(['maxlength' => true, 'placeholder' => 'Ej: Boda Familia Pérez']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'id_tipo_evento')->dropDownList($tiposEvento, ['prompt' => 'Seleccione Tipo...']) ?>
        </div>
        
        <div class="col-md-4">
            <?= $form->field($model, 'id_salon')->dropDownList($salones, ['prompt' => 'Seleccione Salón...']) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'cantidad_personas')->textInput(['type' => 'number']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'fecha_evento')->textInput(['type' => 'date']) ?>
        </div>
        
        <div class="col-md-4">
            <?= $form->field($model, 'hora_inicio')->textInput(['type' => 'time']) ?>
        </div>
        
        <div class="col-md-4">
            <?= $form->field($model, 'hora_fin')->textInput(['type' => 'time']) ?>
        </div>
    </div>

    <?= $form->field($model, 'observaciones')->textarea(['rows' => 3, 'placeholder' => 'Notas internas del staff...']) ?>

    <hr>

    <?php if (!$esNuevo): ?>
        <div class="panel panel-info" style="background-color: #f9f9f9; padding: 15px; border: 1px solid #d9edf7;">
            <h4><i class="fa fa-edit"></i> Detalles del Expediente (Edición)</h4>
            <div class="row">
                <div class="col-md-6"><?= $form->field($model, 'estado')->dropDownList(['Pendiente' => 'Pendiente', 'Confirmada' => 'Confirmada', 'Cancelada' => 'Cancelada']) ?></div>
                <div class="col-md-6"><?= $form->field($model, 'estado_pago')->dropDownList(['Pendiente' => 'Pendiente', 'Parcial' => 'Parcial', 'Pagado' => 'Pagado']) ?></div>
            </div>
            <?= $form->field($model, 'logistica')->textarea(['rows' => 4]) ?>
            <?= $form->field($model, 'menu_opcion')->textarea(['rows' => 4]) ?>
        </div>
    <?php endif; ?>

    <?php if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Crear y Generar Link' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
