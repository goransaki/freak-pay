<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 16:33
 */

namespace api\modules\v1\service;

interface UserService
{
    function getEnrolledUser($id);

    function getPaymentMethods($id);
}