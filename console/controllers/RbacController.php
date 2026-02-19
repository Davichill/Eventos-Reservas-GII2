<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        // Crear rol Administrador
        $admin = $auth->createRole('Administrador');
        $auth->add($admin);

        // Asignar rol al usuario ID 1
        $auth->assign($admin, 1);

        echo "Rol Administrador creado y asignado al usuario 1.\n";
    }
}
