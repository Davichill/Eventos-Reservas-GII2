<?php

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],

    'modules' => [
        'clientes' => ['class' => 'backend\modules\clientes\Module'],
        'empresas' => ['class' => 'backend\modules\empresas\Module'],
        'reservas' => ['class' => 'backend\modules\reservas\Module'],
        'menu_almuerzo_cena' => ['class' => 'backend\modules\menu_almuerzo_cena\Module'],
        'menu_desayuno' => ['class' => 'backend\modules\menu_desayuno\Module'],
        'menu_coctel' => ['class' => 'backend\modules\menu_coctel\Module'],
        'menu_coffee_break' => ['class' => 'backend\modules\menu_coffee_break\Module'],
        'menu_seminario' => ['class' => 'backend\modules\menu_seminario\Module'],
        'mesas' => ['class' => 'backend\modules\mesas\Module'],
        'calendario' => ['class' => 'backend\modules\mesas\Module'],
        'pagos_reservas' => ['class' => 'backend\modules\pagos_reservas\Module'],
        'gridview' => ['class' => '\kartik\grid\Module'],
    ],

    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@vendor/hail812/yii2-adminlte3/src/views'
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii2fullcalendar\yii2fullcalendarAsset' => [
                    'sourcePath' => null,
                    'js' => [],
                    'css' => [],
                ],
                'yii\web\MomentAsset' => [
                    'sourcePath' => null,
                    'js' => [],
                ],
                // DESHABILITAR BOOTSTRAP 5
                'yii\bootstrap5\BootstrapAsset' => false,
                'yii\bootstrap5\BootstrapPluginAsset' => false,

                // FORZAR BOOTSTRAP 4
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => '@npm/bootstrap/dist',
                    'css' => ['css/bootstrap.min.css'],
                    'js' => ['js/bootstrap.bundle.min.js']
                ],

                // CONFIGURAR KARTIK
                'kartik\base\BootstrapAsset' => [
                    'bsDependencyEnabled' => false,
                    'bsVersion' => '4.x',
                ],
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ], // Aquí cierra components

    'params' => $params, // Params queda fuera de components
]; // Fin del return