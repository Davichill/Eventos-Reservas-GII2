<?php

namespace backend\modules\menu_almuerzo_cena\controllers;

use yii\web\Controller;

/**
 * Default controller for the `menu_almuerzo_cena` module
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
