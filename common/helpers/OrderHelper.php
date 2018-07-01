<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 1.7.2018.
 * Time: 06:11
 */

namespace common\helpers;


use common\models\OrderProduct;
use common\models\Orders;
use common\models\Product;

class OrderHelper
{

    public static function getAmount($model)
    {
        if (empty($model)) {
            return 0;
        }

        /** @var Orders $model */
        $orderProducts = $model->getOrderProducts()->all();

        if (empty($orderProducts)) {
            return 0;
        }

        return self::generateAmount($orderProducts);
    }

    /**
     * @param $orderProducts
     * @return float|int
     * @throws \yii\base\InvalidConfigException
     */
    public static function generateAmount($orderProducts)
    {
        $amount = 0;
        /** @var OrderProduct $orderProduct */
        foreach ($orderProducts as $orderProduct) {
            /** @var OrderProduct $product */
            $quantity = ArrayHelper::getValue($orderProduct, 'quantity', 0);
            $amount += \yii\helpers\ArrayHelper::getValue($orderProduct->getProduct()->one(), 'price') * $quantity;
        }

        return $amount.' RSD';
    }
}