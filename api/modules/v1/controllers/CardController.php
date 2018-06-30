<?php

namespace api\modules\v1\controllers;

class CardController extends \api\components\ActiveController
{
    public $modelClass = '';

    public function actionIndex()
    {
        return ['1' => 'test'];
    }
}