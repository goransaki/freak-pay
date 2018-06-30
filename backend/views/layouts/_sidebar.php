<?php

use common\helpers\SidebarHelper;
use yii\widgets\Menu;

?>

<div id="m_ver_menu"
     class="m-aside-menu  m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark "
     data-menu-vertical="true"
     data-menu-scrollable="false" data-menu-dropdown-timeout="500">
    <?= Menu::widget([
        'encodeLabels' => false,
        'itemOptions' => [
            'class' => 'm-menu__item m-menu__item--submenu'
        ],
        'activeCssClass' => 'm-menu__item--active',
        'linkTemplate' => '<a class="m-menu__link m-menu__toggle" href="{url}">{label}</a>',
        'items' => [
            [
                'label' => SidebarHelper::getItem(Yii::t('app', 'Dashboard'), 'flaticon-line-graph'),
                'url' => ['/'],
                'linkOptions' => ['class' => 'm-menu__link m-menu__toggle'],
                'active' => in_array(\Yii::$app->controller->id, ['dashboard']),
            ],
            [
                'label' => SidebarHelper::getItem(Yii::t('app', 'Users'), 'flaticon-users'),
                'url' => ['/users'],
                'linkOptions' => ['class' => 'm-menu__link m-menu__toggle'],
                'active' => in_array(\Yii::$app->controller->id, ['users']),
            ],
            [
                'label' => SidebarHelper::getItem(Yii::t('app', 'Transactions'), 'flaticon-refresh'),
                'url' => ['/transaction'],
                'linkOptions' => ['class' => 'm-menu__link m-menu__toggle'],
                'active' => in_array(\Yii::$app->controller->id, ['transaction']),
            ],
            [
                'label' => SidebarHelper::getItem(Yii::t('app', 'Cards'), 'flaticon-tabs'),
                'url' => ['/card'],
                'linkOptions' => ['class' => 'm-menu__link m-menu__toggle'],
                'active' => in_array(\Yii::$app->controller->id, ['card']),
            ],
        ],
        'options' => ['class' => 'm-menu__nav m-menu__nav--dropdown-submenu-arrow'],
    ]);
    ?>
</div>
