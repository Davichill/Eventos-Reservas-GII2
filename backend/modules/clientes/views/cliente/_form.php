<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\modules\empresas\models\Empresas;
use common\models\User; // Asegúrate de que esta sea la ruta de tu modelo de Usuarios

/* @var $this yii\web\View */
/* @var $model backend\modules\clientes\models\Clientes */
/* @var $form yii\bootstrap4\ActiveForm */

// Carga de datos para los selectores
$listaEmpresas = ArrayHelper::map(Empresas::find()->all(), 'id', 'razon_social');
$listaUsuarios = ArrayHelper::map(User::find()->all(), 'id', 'usuario'); // Cambia 'usuario' por el campo de nombre en tu tabla User

// Lógica de valores por defecto para nuevos registros
if ($model->isNewRecord) {
    $model->id_usuario_creador = Yii::$app->user->identity->id; // Pre-selecciona el logeado
    $model->estado = 'Activo';
    $model->fecha_registro = date('Y-m-d H:i:s');
}
?>

<div class="clientes-form p-2">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <h6 class="text-dark font-weight-bold mb-3 border-bottom pb-2">
                <i class="fas fa-id-card text-warning mr-2"></i> Registro de Cliente
            </h6>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'id_empresa')->dropDownList($listaEmpresas, [
                'prompt' => '-- Seleccione la Empresa Matriz --',
                'class' => 'form-control custom-select border-primary'
            ])->label('Empresa a la que pertenece') ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'identificacion')->textInput([
                'maxlength' => true, 
                'placeholder' => 'Cédula o Pasaporte del cliente'
            ])->label('Cédula del Cliente') ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'cliente_nombre')->textInput(['maxlength' => true, 'placeholder' => 'Nombres']) ?>
        </div>

        <div class="col-md-6">
            <?= $form->field($model, 'cliente_apellido')->textInput(['maxlength' => true, 'placeholder' => 'Apellidos']) ?>
        </div>

        <div class="col-md-12 mt-3">
            <h6 class="text-dark font-weight-bold mb-3 border-bottom pb-2">
                <i class="fas fa-envelope text-warning mr-2"></i> Contacto y Facturación
            </h6>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'cliente_email')->textInput(['maxlength' => true, 'type' => 'email']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'cliente_telefono')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'correo_facturacion')->textInput(['maxlength' => true])->label('Email Facturación') ?>
        </div>

        <div class="col-md-12 mt-3">
            <h6 class="text-dark font-weight-bold mb-3 border-bottom pb-2">
                <i class="fas fa-map-marker-alt text-warning mr-2"></i> Ubicación y Registro
            </h6>
        </div>

        <div class="col-md-8">
            <?= $form->field($model, 'direccion_fiscal')->textInput(['placeholder' => 'Dirección de domicilio o trabajo']) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'estado')->dropDownList([
                'Activo' => 'Activo', 
                'Inactivo' => 'Inactivo', 
            ], ['class' => 'form-control custom-select']) ?>
        </div>

        <div class="col-md-4"><?= $form->field($model, 'ciudad')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'pais')->textInput(['maxlength' => true]) ?></div>
        
        <div class="col-md-4">
            <?= $form->field($model, 'id_usuario_creador')->dropDownList($listaUsuarios, [
                'prompt' => '-- Seleccione Usuario --',
                'class' => 'form-control custom-select bg-light'
            ])->label('Asignar a Usuario') ?>
        </div>
    </div>

    <?= $form->field($model, 'fecha_registro')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'created_at')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'updated_at')->hiddenInput()->label(false) ?>

    <div style="display:none">
        <?= $form->field($model, 'razon_social')->hiddenInput() ?>
        <?= $form->field($model, 'representante_legal')->hiddenInput() ?>
    </div>

    <?php if (!Yii::$app->request->isAjax){ ?>
        <hr>
        <div class="form-group text-right">
            <?= Html::submitButton($model->isNewRecord ? 'Guardar Cliente' : 'Actualizar', [
                'class' => $model->isNewRecord ? 'btn btn-success px-4' : 'btn btn-primary px-4',
                'style' => 'border-radius: 8px;'
            ]) ?>
        </div>
    <?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>