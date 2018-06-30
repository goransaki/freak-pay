<?php

namespace api\modules\v1\controllers;

use common\helpers\ArrayHelper;
use common\models\Orders;
use common\models\User;
use yii\web\NotFoundHttpException;

/**
 * Class CardController
 * @package api\modules\v1\controllers
 */
class OrdersController extends \api\components\ActiveController
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
                    'pending' => ['GET'],
                    'completed' => ['GET'],
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

    /**
     * @param $orderNumber
     * @return array
     */
    public function actionPending($orderNumber)
    {
        $order = Orders::findOne(['id' => $orderNumber, 'status' => Orders::STATUS_PENDING]);

        if (empty($order)) {
            throw new NotFoundHttpException();
        }

        return $order;
    }

    /**
     * @param $orderNumber
     * @return array
     */
    public function actionCompleted($orderNumber)
    {
        $order = Orders::findOne(['id' => $orderNumber, 'status' => Orders::STATUS_COMPLETED]);

        if (empty($order)) {
            throw new NotFoundHttpException();
        }

        return $order;
    }
}