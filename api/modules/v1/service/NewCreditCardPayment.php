<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 16:36
 */

namespace api\modules\v1\service;

class NewCreditCardPayment
{


    var $data;

    /**
     * NewCreditCardPayment constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
}