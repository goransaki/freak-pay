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
                        'text' => 'Last 1h',
                    ],
                    'exporting' => [
                        'enabled' => false
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
                        'categories' => ['-60m', '-50m', '-40m', '-30m', '-20m', '-10m'],
                    ],
                    'series' => [[
                        'name' => 'Transactions',
                        'type' => 'spline',
                        'yAxis' => 1,
                        'data' => [64, 21, 33, 71, 12, 5],
                    ], [
                        'name' => 'Earnings',
                        'type' => 'spline',
                        'data' => [700.0, 600.9, 900.5, 1100.5, 100.2, 60.5],
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
                        'text' => 'Last 8h',
                    ],
                    'yAxis' => [
                        [ // Primary yAxis
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
                        'categories' => ['-6h', '-5h', '-4h', '-3h', '-2h', '-1h'],
                    ],
                    'series' => [[
                        'name' => 'Transactions',
                        'type' => 'spline',
                        'yAxis' => 1,
                        'data' => [572, 216, 323, 450, 920, 115],
                        'tooltip' => [
                            //      'valueSuffix' => ' mm'
                        ]

                    ], [
                        'name' => 'Earnings',
                        'type' => 'spline',
                        'data' => [6289, 2182, 8900, 9900,21000, 1100],
                        'tooltip' => [
                            //         'valueSuffix' => '°C'
                        ]
                    ]],
                    'exporting' => [
                        'enabled' => false
                    ]

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
                        'text' => 'Last 24h',
                    ],
                    'exporting' => [
                        'enabled' => false
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
                        'categories' => ['-24h', '-20h', '-16h', '-12h', '-8h', '-4h'],
                    ],
                    'series' => [[
                        'name' => 'Transactions',
                        'type' => 'spline',
                        'yAxis' => 1,
                        'data' => [1300, 1900, 2300, 1000, 3400, 2500],
                        'tooltip' => [
                            //      'valueSuffix' => ' mm'
                        ],

                    ], [
                        'name' => 'Earnings',
                        'type' => 'spline',
                        'data' => [11000, 19000, 25000, 10100, 30000, 19000],
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

//requestData();
JSHIGHCHARTS;


$this->registerJs($js, \yii\web\View::POS_READY);
