<?php

namespace api\modules\v1\controllers;
use common\models\User;

/**
 * Class CardController
 * @package api\modules\v1\controllers
 */
class OrderController extends \api\components\ActiveController
{
    public $modelClass = User::class;

    /**
     * @return array
     */
    public function actionIndex()
    {
        return ['1' => 'test'];
    }
    public function actionPay()
    {
        return ['1' => 'test'];
    }
}