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
    'clientes' => [
        'class' => 'backend\modules\clientes\Module',
    ],
    'empresas' => [
        'class' => 'backend\modules\empresas\Module',
    ],
    'reservas' => [
        'class' => 'backend\modules\reservas\Module',
    ],
    // AÑADE ESTO:
    'gridview' =>  [
        'class' => '\kartik\grid\Module'
    ],
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
                // 1. Deshabilitar Bootstrap 5 completamente
                'yii\bootstrap5\BootstrapAsset' => false,
                'yii\bootstrap5\BootstrapPluginAsset' => false,

                // 2. Forzar Bootstrap 4 en widgets base
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => '@npm/bootstrap/dist',
                    'css' => ['css/bootstrap.min.css'],
                    'js' => ['js/bootstrap.bundle.min.js']
                ],

                // 3. Configurar AdminLTE (si usas adminlte-asset)
                'dmstr\web\AdminLteAsset' => [
                    'depends' => [
                        'yii\web\YiiAsset',
                        'yii\bootstrap\BootstrapAsset',
                        'yii\bootstrap\BootstrapPluginAsset',
                    ],
                ],

                // 4. Configurar Kartik widgets para Bootstrap 4
                'kartik\base\BootstrapAsset' => [
                    'bsDependencyEnabled' => false, // No cargar bootstrap.css
                    'bsVersion' => '4.x',
                ],
                'kartik\grid\GridViewAsset' => [
                    'bsVersion' => '4.x',
                ],
                'kartik\dialog\DialogAsset' => [
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
            // this is the name of the session cookie used for login on the backend
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
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];
