<?php
$order = $model->getOrder()->one();
$orderProducts = !empty($order) ? $order->getOrderProducts()->all() : [];
?>
<div class="m-portlet__body">
    <div class="tab-content">
        <div class="row">
            <div class="col-md-6">
                <div class="tab-pane active" id="m_tabs_12_1" role="tabpanel">
                    <span><strong>Order: </strong></span><?= \common\helpers\ArrayHelper::getValue($order, 'identifier') ?>
                    <br>
                    <span><strong>User: </strong></span><?= \common\helpers\ArrayHelper::getValue($model->getUser()->one(), 'first_name') . ' ' . \common\helpers\ArrayHelper::getValue($model->getUser()->one(), 'last_name') ?>
                    <br>
                    <span><strong>Payment method: </strong></span><?= \common\helpers\ArrayHelper::getValue($model->getPaymentMethod()->one(), 'type') ?>
                    <br>
                    <span><strong>Store Location: </strong></span>
                    <div id="googleMap<?= $model->id ?>" style="width:100%;height:300px;"></div>
                </div>
            </div>
            <div class="col-md-6">
                <h3>Order</h3>
                <span><strong>Order: </strong></span><?= \common\helpers\ArrayHelper::getValue($order, 'identifier') ?>
                <br><br>
                <span><strong>Products </strong></span>
                <br>

                <?php if (!empty($orderProducts)): ?>
                    <table class="table m-table m-table--head-separator-primary">
                        <thead>
                        <tr>
                            <th>
                                #
                            </th>
                            <th>
                                Name
                            </th>
                            <th>
                                Quantity
                            </th>
                            <th>
                                Amount
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($orderProducts as $orderProduct): ?>
                            <tr>
                                <th scope="row">
                                    <?= $orderProduct->product->code ?>
                                </th>
                                <td>
                                    <?= $orderProduct->product->name ?>
                                </td>
                                <td>
                                    <?= $orderProduct->quantity ?>
                                </td>
                                <td>
                                    <?= $orderProduct->product->price ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <strong> <span>Total Amount: </span><?= \common\helpers\OrderHelper::getAmount($order); ?>
                </strong>
            </div>
        </div>
    </div>
</div>

<script>
    function myMap() {

        var myLatLng1 = {lat: 44.8116601, lng: 20.4639214};

        var map1 = new google.maps.Map(document.getElementById('googleMap1'), {
            zoom: 20,
            center: myLatLng1
        });

        var marker1 = new google.maps.Marker({
            position: myLatLng1,
            map: map1,
            title: 'Store'
        });

        var myLatLng2 = {lat: 44.8104049, lng: 20.4677224};

        var map2 = new google.maps.Map(document.getElementById('googleMap2'), {
            zoom: 20,
            center: myLatLng2
        });

        var marker2 = new google.maps.Marker({
            position: myLatLng2,
            map: map2,
            title: 'Store'
        });
    }
</script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA4R0-vZsg5L5-0zqO1pFUHhHpywYUdOkI&callback=myMap"></script>