<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 11:16
 */

namespace api\modules\v1\controllers;


use common\models\User;

class ApiController extends \api\components\ActiveController
{
    public $modelClass = User::class;

    public function actionPay($orderNumber)
    {
        return ["order_number" => $orderNumber];
    }
}