<div class="m-portlet__body">
    <div class="tab-content">
        <div class="tab-pane active" id="m_tabs_12_1" role="tabpanel">
            <span><strong>Order: </strong></span><?= \common\helpers\ArrayHelper::getValue($model->getOrder()->one(), 'identifier') ?>
            <br>
            <span><strong>Order: </strong></span><?= \common\helpers\ArrayHelper::getValue($model->getUser()->one(), 'first_name') . ' ' . \common\helpers\ArrayHelper::getValue($model->getUser()->one(), 'last_name') ?>
            <br>
            <span><strong>Payment method: </strong></span><?= \common\helpers\ArrayHelper::getValue($model->getPaymentMethod()->one(), 'type') ?>
            <br>
            <strong> <span>Amount: </span><?= \common\helpers\OrderHelper::getAmount($model->getOrder()->one()); ?>
            </strong>
            <br>
        </div>
    </div>
</div>