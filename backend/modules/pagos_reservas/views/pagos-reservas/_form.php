<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\modules\pagos_reservas\models\PagosReservas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="pagos-reservas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_reserva')->textInput() ?>

    <?= $form->field($model, 'monto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_pago')->textInput() ?>

    <?= $form->field($model, 'metodo_pago')->dropDownList([ 'Transferencia' => 'Transferencia', 'Tarjeta' => 'Tarjeta', 'Efectivo' => 'Efectivo', 'Cheque' => 'Cheque', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'referencia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tipo_pago')->dropDownList([ 'Deposito 1' => 'Deposito 1', 'Deposito 2' => 'Deposito 2', 'Saldo Final' => 'Saldo Final', 'Adicional' => 'Adicional', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'notas')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'registrado_por')->textInput() ?>

  
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
