<?php

namespace api\modules\v1\controllers;

/**
 * Class CardController
 * @package api\modules\v1\controllers
 */
class CardController extends \api\components\ActiveController
{
    public $modelClass = '';

    /**
     * @return array
     */
    public function actionIndex()
    {
        return ['1' => 'test'];
    }
}