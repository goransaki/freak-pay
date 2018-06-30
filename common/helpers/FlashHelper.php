<?php
/**
 * Created by PhpStorm.
 * User: Boris Matic
 * Date: 9/21/2016
 * Time: 10:09 PM
 */
namespace common\helpers;

class FlashHelper
{
    public static function setSuccess($message)
    {
        self::setFlashMessage('success', $message);
    }

    public static function setError($message)
    {
        self::setFlashMessage('error', $message);
    }

    protected static function setFlashMessage($type, $message)
    {
        \Yii::$app->getSession()->addFlash($type, $message);
    }
}