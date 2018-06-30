<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 16:38
 */

namespace api\modules\v1\service;

use common\models\Orders;
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

    private function extractOrderDetails(Orders $order)
    {
        return [
            'id' => $order->id
        ];
    }

    /**
     * @param $orderNumber
     * @return array
     * @throws NotFoundHttpException
     */
    public function getPendingOrder($orderNumber)
    {
        $order = Orders::findOne(['id' => $orderNumber, 'status' => Orders::STATUS_PENDING]);

        if (empty($order)) {
            throw new NotFoundHttpException();
        }

        return $this->extractOrderDetails($order);
    }

    /**
     * @param $orderNumber
     * @return array
     * @throws NotFoundHttpException
     */
    public function getCompletedOrder($orderNumber)
    {
        $order = Orders::findOne(['id' => $orderNumber, 'status' => Orders::STATUS_COMPLETED]);

        if (empty($order)) {
            throw new NotFoundHttpException();
        }

        return $this->extractOrderDetails($order);
    }
}