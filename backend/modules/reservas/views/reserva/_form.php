<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

$esNuevo = $model->isNewRecord;

// Respaldo de listas
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

    <div class="card card-outline card-primary mb-3">
        <div class="card-header">
            <h3 class="card-title">Datos Básicos del Evento</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'id_cliente')->dropDownList($clientes, [
                        'prompt' => '--- Seleccione Cliente ---',
                        'class' => 'form-control select2'
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
        </div>
    </div>

    <div class="card card-outline card-info mb-3">
        <div class="card-header">
            <h3 class="card-title">Datos para Contrato / Firma</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <?= $form->field($model, 'firma_nombre')->textInput(['placeholder' => 'Nombre o Razón Social que firma']) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'firma_identificacion')->textInput(['placeholder' => 'RUC o Cédula']) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'contacto_evento_nombre')->textInput(['placeholder' => 'Nombre del responsable el día del evento']) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'contacto_evento_telefono')->textInput(['placeholder' => 'Teléfono directo']) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-warning mb-3">
        <div class="card-header">
            <h3 class="card-title">Logística y Montaje</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <?= $form->field($model, 'id_mesa')->dropDownList(
                        ArrayHelper::map(\backend\modules\mesas\models\Mesas::find()->all(), 'id', 'nombre'),
                        ['prompt' => 'Seleccione Tipo de Montaje...']
                    ) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'manteleria')->textInput(['placeholder' => 'Ej: Blanco, Satinado...']) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'color_servilleta')->textInput(['placeholder' => 'Color de servilletas']) ?>
                </div>
            </div>
            <?= $form->field($model, 'equipos_audiovisuales')->textInput(['placeholder' => 'Proyector, micrófonos, parlantes...']) ?>
            <?= $form->field($model, 'logistica')->textarea(['rows' => 3, 'placeholder' => 'Detalles de montaje, accesos, etc.']) ?>
        </div>
    </div>

    <div class="card card-outline card-secondary mb-3">
        <div class="card-body">
            <?= $form->field($model, 'observaciones')->textarea(['rows' => 2, 'placeholder' => 'Notas internas generales...']) ?>

            <?php if (!$esNuevo): ?>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'estado')->dropDownList(['Pendiente' => 'Pendiente', 'Confirmada' => 'Confirmada', 'Cancelada' => 'Cancelada']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'estado_pago')->dropDownList(['Pendiente' => 'Pendiente', 'Parcial' => 'Parcial', 'Pagado' => 'Pagado']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'total_evento')->textInput(['type' => 'number', 'step' => '0.01']) ?>
                    </div>
                </div>
                <?= $form->field($model, 'menu_opcion')->textarea(['rows' => 3, 'placeholder' => 'Detalle del menú seleccionado']) ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!Yii::$app->request->isAjax) { ?>
        <div class="form-group mt-3">
            <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-save"></i> Crear y Generar Link' : '<i class="fa fa-refresh"></i> Actualizar', [
                'class' => $model->isNewRecord ? 'btn btn-success btn-lg btn-block' : 'btn btn-primary btn-lg btn-block'
            ]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>

</div>