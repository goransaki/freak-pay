<?php

use dosamigos\highcharts\HighCharts;

$this->title = 'Freak pay';
?>
<?php \common\widgets\Panel::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <?= HighCharts::widget([
                'clientOptions' => [
                    'chart' => [
                        'type' => 'container'
                    ],
                    'title' => [
                        'text' => 'Transactions 24h',
                    ],
                    'yAxis' => [
                        [ // Primary yAxis
                            'labels' => [
                                //          'format' => '{value}°C',
                            ],
                            'title' => [
                                'text' => 'Transactions',
                            ]
                        ], [ // Secondary yAxis
                            'title' => [
                                'text' => 'Earnings',
                            ],
                            'labels' => [
                                //       'format' => '{value} mm',
                            ],
                            'opposite' => true
                        ]
                    ],
                    'xAxis' => [
                        'crosshair' => true,
                        'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    ],
                    'series' => [[
                        'name' => 'Transactions',
                        'type' => 'spline',
                        'yAxis' => 1,
                        'data' => [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                        'tooltip' => [
                            //      'valueSuffix' => ' mm'
                        ]

                    ], [
                        'name' => 'Earnings',
                        'type' => 'spline',
                        'data' => [700.0, 600.9, 900.5, 500.5, 700.2, 800.5, 200.2, 260.5, 230.3, 180.3, 130.9, 900.6],
                        'tooltip' => [
                            //         'valueSuffix' => '°C'
                        ]
                    ]]

                ]
            ]);
            ?>
        </div>
        <div class="col-md-4">
            <?= HighCharts::widget([
                'clientOptions' => [
                    'chart' => [
                        'type' => 'container'
                    ],
                    'title' => [
                        'text' => 'Transactions 24h',
                    ],
                    'yAxis' => [
                        [ // Primary yAxis
                            'labels' => [
                                //          'format' => '{value}°C',
                            ],
                            'title' => [
                                'text' => 'Transactions',
                            ]
                        ], [ // Secondary yAxis
                            'title' => [
                                'text' => 'Earnings',
                            ],
                            'labels' => [
                                //       'format' => '{value} mm',
                            ],
                            'opposite' => true
                        ]
                    ],
                    'xAxis' => [
                        'crosshair' => true,
                        'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    ],
                    'series' => [[
                        'name' => 'Transactions',
                        'type' => 'spline',
                        'yAxis' => 1,
                        'data' => [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                        'tooltip' => [
                            //      'valueSuffix' => ' mm'
                        ]

                    ], [
                        'name' => 'Earnings',
                        'type' => 'spline',
                        'data' => [700.0, 600.9, 900.5, 500.5, 700.2, 800.5, 200.2, 260.5, 230.3, 180.3, 130.9, 900.6],
                        'tooltip' => [
                            //         'valueSuffix' => '°C'
                        ]
                    ]]

                ]
            ]);
            ?>

        </div>
        <div class="col-md-4">
            <?= HighCharts::widget([
                'clientOptions' => [
                    'chart' => [
                        'type' => 'container'
                    ],
                    'title' => [
                        'text' => 'Transactions 24h',
                    ],
                    'yAxis' => [
                        [ // Primary yAxis
                            'labels' => [
                                //          'format' => '{value}°C',
                            ],
                            'title' => [
                                'text' => 'Transactions',
                            ]
                        ], [ // Secondary yAxis
                            'title' => [
                                'text' => 'Earnings',
                            ],
                            'labels' => [
                                //       'format' => '{value} mm',
                            ],
                            'opposite' => true
                        ]
                    ],
                    'xAxis' => [
                        'crosshair' => true,
                        'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    ],
                    'series' => [[
                        'name' => 'Transactions',
                        'type' => 'spline',
                        'yAxis' => 1,
                        'data' => [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                        'tooltip' => [
                            //      'valueSuffix' => ' mm'
                        ],

                    ], [
                        'name' => 'Earnings',
                        'type' => 'spline',
                        'data' => [700.0, 600.9, 900.5, 500.5, 700.2, 800.5, 200.2, 260.5, 230.3, 180.3, 130.9, 900.6],
                        'tooltip' => [
                            //         'valueSuffix' => '°C'
                        ]
                    ]]

                ]
            ]);
            ?>
        </div>
    </div>

<?php \common\widgets\Panel::end(); ?>
<?php

$js = <<<JSHIGHCHARTS
console.log('radi');
var charts = [
{
    id : "#w0",
    period : "hours_1"
},
{
    id : "#w1",
    period : "hours_6"
},
{
    id : "#w2",
    period : "hours_24"
}
];
function requestData() {
    $.ajax({
        url: '/statistics/graph-info',
        success: function(data) {
        
        for(i in charts) {
        console.log(charts[i])
            var id = charts[i].id;
            var chart = $(id).highcharts();
            chart.series[0].setData(data[charts[i].period][0]);
            chart.series[1].setData(data[charts[i].period][1]);
        }   
            // call it again after one second
            setTimeout(requestData, 1000);    
        },
        cache: false
    });
}

requestData();
JSHIGHCHARTS;


$this->registerJs($js, \yii\web\View::POS_READY);
