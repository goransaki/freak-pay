<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 16:38
 */

namespace api\modules\v1\service;

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

    public function getPendingOrder($orderNumber)
    {
        // TODO: Implement getPendingOrder() method.
    }

    public function getCompletedOrder($orderNumber)
    {
        // TODO: Implement getCompletedOrder() method.
    }
}