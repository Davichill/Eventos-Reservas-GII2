<?php

namespace backend\modules\menu_coctel\controllers;

use yii\web\Controller;

/**
 * Default controller for the `menu_coctel` module
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
