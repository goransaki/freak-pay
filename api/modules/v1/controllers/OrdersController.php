<?php

namespace api\modules\v1\controllers;

use api\modules\v1\service\EWalletPayment;
use api\modules\v1\service\NewCreditCardPayment;
use api\modules\v1\service\OrderService;
use api\modules\v1\service\OrderServiceImpl;
use api\modules\v1\service\SavedCreditCardPayment;
use common\models\User;

/**
 * Class CardController
 * @package api\modules\v1\controllers
 */
class OrdersController extends \api\components\ActiveController
{
    public $modelClass = User::class;

    /**
     * @var OrderService
     */
    private $api;

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->api = new OrderServiceImpl();
    }


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
        return $this->api->payWithCreditCard($orderNumber, new NewCreditCardPayment(\Yii::$app->request->post()));
    }

    /**
     * @param $orderNumber
     * @return array
     */
    public function actionPayWithSavedCreditCard($orderNumber)
    {
        return $this->api->payWithSavedCreditCard($orderNumber, new SavedCreditCardPayment(\Yii::$app->request->post()));
    }

    /**
     * @param $orderNumber
     * @return array
     */
    public function actionPayEwallet($orderNumber)
    {
        return $this->api->payWithEwallet($orderNumber, new EWalletPayment(\Yii::$app->request->post()));
    }

    /**
     * @param $orderNumber
     * @return array
     */
    public function actionPending($orderNumber)
    {
        return $this->api->getPendingOrder($orderNumber);
    }

    /**
     * @param $orderNumber
     * @return array
     */
    public function actionCompleted($orderNumber)
    {
        return $this->api->getCompletedOrder($orderNumber);
    }
}