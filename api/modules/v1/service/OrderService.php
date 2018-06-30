<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 16:34
 */

namespace api\modules\v1\service;

interface OrderService
{
    public function payWithCreditCard($orderNumber, NewCreditCardPayment $newCreditCardPayment);

    public function payWithSavedCreditCard($orderNumber, SavedCreditCardPayment $savedCreditCardPayment);

    public function payWithEwallet($orderNumber, EWalletPayment $payment);

    public function getPendingOrder($orderNumber);

    public function getCompletedOrder($orderNumber);
}