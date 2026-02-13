<?php
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap4\ActiveForm */
/* @var $model \common\models\LoginForm */

// Esto asegura que no se use ningún layout externo
$this->context->layout = false; 

$this->title = 'Login - GO Quito Hotel';
?>

<div class="login-page d-flex align-items-center justify-content-center" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="login-box shadow-sm bg-white p-5" style="width: 450px; border-radius: 10px; border: 1px solid #eee;">
        
        <div class="text-center mb-4">
            <?= Html::img('@web/img/logo-go-quito.png', ['style' => 'width: 150px;']) ?>
            <p class="text-muted mt-2" style="font-size: 0.9rem;">Panel Administrativo</p>
        </div>

        <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <label class="font-weight-bold" style="color: #2c3e50;">Usuario</label>
            <?= $form->field($model, 'username')->textInput([
                'placeholder' => 'Ingrese su usuario',
                'style' => 'border-radius: 8px; padding: 12px;'
            ])->label(false) ?>

            <label class="font-weight-bold" style="color: #2c3e50;">Contraseña</label>
            <?= $form->field($model, 'password')->passwordInput([
                'placeholder' => 'Ingrese su contraseña',
                'style' => 'border-radius: 8px; padding: 12px;'
            ])->label(false) ?>

            <div class="form-group mt-4">
                <?= Html::submitButton('Acceder al Panel', [
                    'class' => 'btn btn-block text-white', 
                    'style' => 'background-color: #001f3f; border-radius: 8px; padding: 12px; font-weight: bold;',
                    'name' => 'login-button'
                ]) ?>
            </div>

        <?php ActiveForm::end(); ?>

        <div class="text-center mt-5 text-muted" style="font-size: 0.75rem;">
            <p>© 2026 GO Quito Hotel. Todos los derechos reservados.<br>
            <span class="font-italic">Solo personal autorizado</span></p>
        </div>
    </div>
</div>