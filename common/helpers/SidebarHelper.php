<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 3/28/2018
 * Time: 2:55 PM
 */

namespace common\helpers;

class SidebarHelper
{
    public static function getItem($name, $icon = '')
    {
        return '<i class="m-menu__link-icon ' . $icon .'"></i>' .
            '<span class="m-menu__link-text">' . $name . '</span>'
            . '<i class="m-menu__ver-arrow la la-angle-right"></i>';
    }
}