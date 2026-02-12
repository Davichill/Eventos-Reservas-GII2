<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\menu_coctel\models\menuCoctel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-coctel-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'] // 1. OBLIGATORIO para subir archivos
    ]); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'categoria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subcategoria')->textInput(['maxlength' => true]) ?>

    <?php // 2. Cambiamos textInput por fileInput y usamos la variable virtual imageFile ?>
    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?= $form->field($model, 'estado')->dropDownList([1 => 'Activo', 0 => 'Inactivo']) ?>

    <?php if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>