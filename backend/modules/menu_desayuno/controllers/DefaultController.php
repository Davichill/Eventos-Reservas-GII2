<?php

namespace backend\modules\menu_desayuno\controllers;

use yii\web\Controller;

/**
 * Default controller for the `menu_desayuno` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
