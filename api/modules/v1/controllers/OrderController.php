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
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'pay-credit-card' => ['POST'],
                    'pay-with-saved-credit-card' => ['POST'],
                    'pay-ewallet' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @param $orderNumber
     * @return array
     */
    public function actionPayCreditCard($orderNumber)
    {
        return ['1' => $orderNumber];
    }

    /**
     * @param $orderNumber
     * @return array
     */
    public function actionPayWithSavedCreditCard($orderNumber)
    {
        return ['1' => $orderNumber];
    }

    /**
     * @param $orderNumber
     * @return array
     */
    public function actionPayEwallet($orderNumber)
    {
        return ['1' => $orderNumber];
    }
}