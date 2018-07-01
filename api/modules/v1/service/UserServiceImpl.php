<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 16:39
 */

namespace api\modules\v1\service;

use common\models\PaymentMethod;
use common\models\User;
use yii\web\HttpException;

class UserServiceImpl implements UserService
{

    /**
     * @param $id
     * @return array
     * @throws HttpException
     */
    function getEnrolledUser($id)
    {
        $user = User::findOne(['id' => $id]);
        if ($user == null) {
            throw new HttpException(404, 'User not found');
        } else {
            return [
                'first_name' => $user->first_name,
                'last_name' => $user->last_name
            ];
        }

        // TODO: Implement getEnrolledUser() method.
    }

    function getPaymentMethods($id)
    {
        PaymentMethod::find()
            ->where(['user_id' => $id])
            ->orderBy('sort_key')

        ;
        // TODO: Implement getPaymentMethods() method.
    }
}