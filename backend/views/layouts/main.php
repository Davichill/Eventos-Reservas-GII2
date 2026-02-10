<?php

/** @var \yii\web\View $this */
/** @var string $content */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap4\Breadcrumbs; // CAMBIADO a bootstrap4
use yii\bootstrap4\Html;        // CAMBIADO a bootstrap4
use yii\bootstrap4\Nav;         // CAMBIADO a bootstrap4
use yii\bootstrap4\NavBar;      // CAMBIADO a bootstrap4
use yii\bootstrap4\Modal;       // AÑADIDO
use johnitvn\ajaxcrud\CrudAsset; // AÑADIDO

AppAsset::register($this);
CrudAsset::register($this); // REGISTRAMOS LOS SCRIPTS DE AJAXCRUD
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <?php $this->head() ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <header>
        <?php
        NavBar::begin([
            'brandLabel' => Yii::$app->name,
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
            ],
        ]);
        $menuItems = [
            ['label' => 'Home', 'url' => ['/site/index']],
        ];

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav mr-auto'], // En b4 es mr-auto, no me-auto
            'items' => $menuItems,
        ]);

        if (Yii::$app->user->isGuest) {
            echo Html::tag('div', Html::a('Login', ['/site/login'], ['class' => ['btn btn-link login text-decoration-none']]), ['class' => ['d-flex']]);
        } else {
            echo Html::beginForm(['/site/logout'], 'post', ['class' => 'form-inline']) // form-inline para b4
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout text-decoration-none']
                )
                . Html::endForm();
        }
        NavBar::end();
        ?>
    </header>

    <main role="main" class="flex-shrink-0" style="padding-top: 60px;">
        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-3 text-muted">
        <div class="container">
            <p class="float-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
            <p class="float-right"><?= Yii::powered() ?></p>
        </div>
    </footer>

    <?php Modal::begin([
        "id" => "ajaxCrudModal",
        "footer" => "",
        "options" => [
            "class" => "modal fade", // 'fade' para que entre suave
            "tabindex" => false,
        ],
    ]);
    Modal::end(); ?>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage(); ?>