<?php

namespace backend\modules\menu_coffee_break\controllers;

use yii\web\Controller;

/**
 * Default controller for the `menu_coffee_break` module
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
