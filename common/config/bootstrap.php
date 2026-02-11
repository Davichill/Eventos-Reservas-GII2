<?php

Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
// Esto define la ruta física absoluta correctamente
Yii::setAlias('@bower', dirname(dirname(__DIR__)) . '/vendor/bower-asset');