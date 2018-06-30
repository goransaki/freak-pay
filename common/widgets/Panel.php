<?php

namespace common\widgets;


use common\helpers\ChargebackMenuHelper;
use Yii;
use yii\base\Widget;
use yii\bootstrap\Html;
use yii\bootstrap\Nav;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Inflector;
use yii\widgets\Menu;


class Panel extends Widget
{
    public $htmlOptions = ['class' => 'panel custom-panel'];

    public $menuItems = [];
    public $showMenuItems = false;
    public $navOptions = [];

    protected $defaultNavClass = 'nav-tabs nav custom-panel-tabs';

    public $moduleName = '';

    public function init()
    {
        parent::init();

        if (empty($this->menuItems)) {
           // $this->menuItems = ChargebackMenuHelper::getDefaultItems();
        }
    }

    protected function prepareNavOptions()
    {
        if(empty($this->navOptions['options']) || empty($this->navOptions['options']['class'])) {
            $this->navOptions['options']['class'] = $this->defaultNavClass;
        }
        $this->navOptions['items'] = $this->getPreparedMenuItems();
    }

    public static function begin($config = [])
    {
        /* @var $self self */
        $self = parent::begin($config);

        echo Html::beginTag('div', $self->htmlOptions);

        if ($self->menuItems && $self->showMenuItems) {
            $self->prepareNavOptions($self);
            echo Nav::widget($self->navOptions);
        }

        echo Html::beginTag('div', ['class' => 'panel-body']);
        return $self;
    }

    public static function end()
    {
        echo Html::endTag('div');
        echo Html::endTag('div');
        return parent::end();
    }

    private function getPreparedMenuItems()
    {
        $menuItems = [];
        foreach ($this->menuItems as $item) {
            $menuItems[] = $this->getMenuItemConfig($item);
        }

        return $menuItems;
    }

    private function getMenuItemConfig($item)
    {
        $url = Yii::$app->request->resolve()[0];

        $config = [];
        $config['label'] = $item['label'];
        $config['url'] = empty($this->moduleName) ? Url::to("/{$item['controller']}/{$item['action']}") : Url::to("/{$this->moduleName}/{$item['controller']}/{$item['action']}");
        $config['linkOptions'] = ['class' => 'btn-nav-control'];
        $config['active'] = in_array($url, [
            "{$item['controller']}/{$item['action']}",
            "{$item['controller']}/view",
            "{$this->moduleName}/{$item['controller']}/{$item['action']}",
            "{$this->moduleName}/{$item['controller']}",
            "{$this->moduleName}/{$item['controller']}/view",
        ]);

        return $config;
    }
}