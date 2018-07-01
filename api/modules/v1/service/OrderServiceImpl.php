<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 16:38
 */

namespace api\modules\v1\service;

use common\models\OrderProduct;
use common\models\Orders;
use common\models\OrdersSearch;
use yii\web\NotFoundHttpException;

class OrderServiceImpl implements OrderService
{

    public function payWithCreditCard($orderNumber, NewCreditCardPayment $newCreditCardPayment)
    {
        // TODO: Implement payWithCreditCard() method.
    }

    public function payWithSavedCreditCard($orderNumber, SavedCreditCardPayment $savedCreditCardPayment)
    {
        // TODO: Implement payWithSavedCreditCard() method.
    }

    public function payWithEwallet($orderNumber, EWalletPayment $payment)
    {
        // TODO: Implement payWithEwallet() method.
    }

    /**
     * @param $orderNumber
     * @return array
     * @throws NotFoundHttpException
     */
    public function getPendingOrder($orderNumber)
    {
        $order = Orders::findOne(['id' => $orderNumber]);

        if (empty($order)) {
            throw new NotFoundHttpException("Order not found");
        }

        return $order;
    }

    /**
     * @param $orderNumber
     * @return array
     * @throws NotFoundHttpException
     */
    public function getCompletedOrder($orderNumber)
    {
        $order = Orders::findOne(['id' => $orderNumber]);

        if (empty($order)) {
            throw new NotFoundHttpException("Order not found");
        }

        return $order;
    }
}