<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\menu_almuerzo_cena\models\MenuAlmuerzoCena */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-almuerzo-cena-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'] // OBLIGATORIO para archivos
    ]); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tiempo')->dropDownList([ 
        'Entradas' => 'Entradas', 
        'Plato Fuerte' => 'Plato Fuerte', 
        'Postres' => 'Postres', 
    ], ['prompt' => 'Seleccione tiempo...']) ?>

    <?= $form->field($model, 'subcategoria')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

     

    <?php if (!Yii::$app->request->isAjax){ ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Crear' : 'Actualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
</div>
