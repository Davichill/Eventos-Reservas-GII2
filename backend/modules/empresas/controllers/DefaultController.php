<?php

namespace backend\modules\empresas\controllers;

use yii\web\Controller;

/**
 * Default controller for the `empresas` module
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
