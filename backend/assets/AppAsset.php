<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/base/vendors.bundle.css',
        'css/style.bundle.css',
        'plugins/notification/notification.css',
        // 'plugins/bootstrap-sweetalert/sweet-alert.css',
        'css/custom.css',
        'css/style.bundle.css',
        'css/datatable.css',

    ];
    public $js = [
        'js/SearchToggle.js',
        'js/main.js',
        //'js/vendors.bundle.js',
        'js/scripts.bundle.js',
        'plugins/notification/notify.min.js',
        //'plugins/notification/notify-metro.js',
        // 'plugins/bootstrap-sweetalert/sweet-alert.min.js',
        'js/tmpl.min.js',
        //'js/main.ui.js',
        'js/custom.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
