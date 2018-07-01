<div class="col-md-6">
    <div class="m-portlet m-portlet--tabs m-portlet--success m-portlet--head-solid-bg m-portlet--bordered">
        <?= $this->render('transaction_header', ['model' => $model]) ?>
        <?= $this->render('order_detail', ['model' => $model]) ?>
    </div>
</div>