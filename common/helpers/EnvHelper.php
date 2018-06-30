<?php
/**
 * Created by PhpStorm.
 * User: ericmaicon
 * Date: 09/02/16
 * Time: 09:25
 */

namespace common\helpers;

class EnvHelper
{

    /**
     * @return string
     */
    public static function getEnvPrefix()
    {
        return defined('YII_ENV') && YII_ENV !== 'prod' ? YII_ENV . '__' : '';
    }
    
    public static function getTokenKey()
    {
        return YII_ENV . '_token';
    }

    public static function getLabel()
    {
        if(!defined('YII_ENV')) {
            return '--';
        }

        return ucfirst(YII_ENV);
    }
}