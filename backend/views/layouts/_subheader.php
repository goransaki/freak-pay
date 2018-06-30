<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 3/30/2018
 * Time: 10:42 AM
 */

use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

?>
<div class="m-subheader ">
    <div class="d-flex align-items-center">
        <div class="mr-auto">
            <h3 class="m-subheader__title <?= isset($this->params['breadcrumbs']) ? 'm-subheader__title--separator' : ''; ?>">
                <?= $this->title ?>
            </h3>
            <?= Breadcrumbs::widget([
                'itemTemplate' => "<li class='m-nav__separator'>-</li><li class='m-nav__item'>{link}</li>\n",
                'activeItemTemplate' => "<li class='m-nav__separator'>-</li><li class='m-nav__item'>{link}</li>\n",
                'encodeLabels' => false,
                'options' => ['class' => 'm-subheader__breadcrumbs m-nav m-nav--inline'],
                'homeLink' => [
                    'label' => Html::tag('span', '<i class="m-nav__link-icon la la-home"></i>', ['class' => 'm-nav__link-text']),
                    'url' => 'dashboard',
                    'class' => 'm-nav__link',
                    'template' => "<li class='m-nav__separator'></li><li class='m-nav__item'>{link}</li>\n"
                ],
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]); ?>
        </div>
    </div>
</div>
