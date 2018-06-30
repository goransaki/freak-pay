<?php

use backend\widgets\Alert;
use yii\bootstrap\Modal;
use yii\helpers\Html;


/* @var $this \yii\web\View */
/* @var $content string */

\backend\assets\AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
        <script>
            WebFont.load({
                google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
                active: function () {
                    sessionStorage.fonts = true;
                }
            });
        </script>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
    <?php $this->beginBody() ?>
    <!-- begin:: Page -->
    <div class="m-grid m-grid--hor m-grid--root m-page">

        <?= $this->render('_navbar'); ?>

        <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
            <!-- BEGIN: Left Aside -->
            <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
                <i class="la la-close"></i>
            </button>
            <div id="m_aside_left" class="m-grid__item	m-aside-left  m-aside-left--skin-dark ">
                <!-- BEGIN: Aside Menu -->
                <?= $this->render('_sidebar') ?>
                <!-- END: Aside Menu -->
            </div>
            <!-- END: Left Aside -->
            <div class="m-grid__item m-grid__item--fluid m-wrapper">
                <?= $this->render('_subheader'); ?>
                <div class="m-content">
                    <?= \common\widgets\Alert::widget(); ?>
                    <?= $content; ?>
                </div>
            </div>
        </div>
        <!-- end:: Body -->
        <?= $this->render('_footer'); ?>
    </div>

    <?php $this->endBody() ?>
    </body>

    </html>
<?php $this->endPage() ?>