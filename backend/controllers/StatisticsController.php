<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 6/30/2018
 * Time: 5:13 PM
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;

class StatisticsController extends Controller
{
    public function actionGraphInfo()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

       // $minuteTimes = $this->getMinuteTimes();
       // $hourlyTimes = $this->getHourlyTimes();
       // $quadHourlyTimes = $this->getQuadHourlyTimes();

        $data = [
            'hours_1' => [
                [49.9, 71.5, 106.4, 129.2, 144.0, 176.0],
                [11.9, 1.5, 106.4, 129.2, 144.0, 111.0]
            ],
            'hours_6' => [
                [49.9, 71.5, 106.4, 129.2, 144.0, 176.0],
                [11.9, 1.5, 106.4, 129.2, 144.0, 111.0]
            ],
            'hours_24' => [
                [49.9, 71.5, 106.4, 129.2, 144.0, 176.0],
                [11.9, 1.5, 106.4, 129.2, 144.0, 111.0]
            ],
        ];


        return $data;
    }

    public function getMinuteTimes()
    {
        $loopDate = time();
        $minuteTimes = [];

        for ($i = 0; $i < 6; $i++) {
            $minutePeriod = [];
            $minutePeriod['end_time'] = date('Y-m-d H:i:s', $loopDate);
            $loopDate = $loopDate - (10 * 60);
            $minutePeriod['start_time'] = date('Y-m-d H:i:s', $loopDate);
            $minuteTimes[] = $minutePeriod;
        }

        return $minuteTimes;
    }

    public function getHourlyTimes()
    {
        $loopDate = time();
        $minuteTimes = [];

        for ($i = 0; $i < 6; $i++) {
            $minutePeriod = [];
            $minutePeriod['end_time'] = date('Y-m-d H:i:s', $loopDate);
            $loopDate = $loopDate - (60 * 60);
            $minutePeriod['start_time'] = date('Y-m-d H:i:s', $loopDate);
            $minuteTimes[] = $minutePeriod;
        }

        return array_reverse($minuteTimes);
    }

    public function getQuadHourlyTimes()
    {
        $loopDate = time();
        $minuteTimes = [];

        for ($i = 0; $i < 6; $i++) {
            $minutePeriod = [];
            $minutePeriod['end_time'] = date('Y-m-d H:i:s', $loopDate);
            $loopDate = $loopDate - (4 * 60 * 60);
            $minutePeriod['start_time'] = date('Y-m-d H:i:s', $loopDate);
            $minuteTimes[] = $minutePeriod;
        }

        return $minuteTimes;
    }

    /**
     * @param $quadHourlyTimes
     * @param $results
     * @return array
     * @throws \yii\db\Exception
     */
    public function getGraphSums($timeLimits)
    {
        $results = [];
        foreach ($timeLimits as $timeLimit) {
            $result = Yii::$app->db
                ->createCommand("select COUNT(DISTINCT transaction.id) as transaction_count, sum(quantity * price) as earnings
                from `transaction`
                inner join order_product on transaction.order_id = order_product.order_id
                inner join product on order_product.product_id = product.id
                where transaction.updated_at > :start_time and transaction.updated_at < :end_time",
                    [':start_time' => $timeLimit['start_time'], ':end_time' => $timeLimit['end_time']])
                ->queryAll();
            $results[] = $result;
        }

        $transactionCounts = [];
        $earningSums = [];

        foreach ($results as $result) {
            $transactionCounts[] = $result[0]['transaction_count'];
            $earningSums[] = empty($result[0]['earnings']) ? '0' : $result[0]['earnings'] ;
        }

        return [$transactionCounts, $earningSums];
    }
}