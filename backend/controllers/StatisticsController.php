<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 6/30/2018
 * Time: 5:13 PM
 */

namespace backend\controllers;


use yii\web\Controller;

class StatisticsController extends Controller
{
    public function actionGraphInfo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $data = [
            'hours_1' => [
                [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                [11.9, 1.5, 106.4, 129.2, 144.0, 111.0, 135.6, 118.5, 116.4, 194.1, 95.6, 54.4]
            ],
            'hours_6' => [
                [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                [11.9, 1.5, 106.4, 129.2, 144.0, 111.0, 135.6, 118.5, 116.4, 194.1, 95.6, 54.4]
            ],
            'hours_24' => [
                [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                [11.9, 1.5, 106.4, 129.2, 144.0, 111.0, 135.6, 118.5, 116.4, 194.1, 95.6, 54.4]
            ],
        ];


        return $data;
    }
}