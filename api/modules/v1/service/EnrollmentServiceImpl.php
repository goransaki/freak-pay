<?php

namespace api\modules\v1\service;

use common\models\Card;
use common\models\Device;
use yii\web\HttpException;

/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 16:38
 */
class EnrollmentServiceImpl implements EnrollmentService
{

    /**
     *
     * Return ['status' => 'enrolled | not_enrolled', '?user_id']
     *
     * @param $id
     * @param $type
     * @return mixed
     * @throws HttpException
     */
    public function enrollmentStatus($id, $type)
    {

        if ($type === 'card') {
            return $this->enrollmentStatusCard($id);
        } else {
            return $this->enrollmentStatusDevice($id);
        }
    }

    /**
     * @param $nfcTag
     * @return int
     * @throws HttpException
     */
    private function enrollmentStatusCard($nfcTag)
    {
        $card = Card::findOne([
            'nfc_tag' => $nfcTag
        ]);

        if ($card == null) {
            return $this->notEnrolled();
        } else {
            return $this->enrolled($card->user_id);
        }
    }

    private function enrolled($userId)
    {
        return [
            'status' => 'enrolled',
            'user_id' => $userId
        ];
    }

    private function notEnrolled()
    {
        return [
            'status' => 'not_enrolled'
        ];
    }

    private function enrollmentStatusDevice($id)
    {
        $device = Device::findOne([
            'nfc_tag' => $id
        ]);

        if ($device == null) {
            return $this->notEnrolled();
        } else {
            return $this->enrolled($device->user_id);
        }
    }
}