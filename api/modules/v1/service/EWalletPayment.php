<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 16:37
 */

namespace api\modules\v1\service;

class EWalletPayment
{

    /**
     * @var array
     */
    var $data;

    /**
     * EWalletPayment constructor.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
}