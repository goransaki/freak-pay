<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 11:16
 */

namespace api\modules\v1\controllers;


class ApiController extends \api\components\ActiveController
{
    public function actionPayCC($orderNumber) {
        return ["order_number" => $orderNumber];
    }
}