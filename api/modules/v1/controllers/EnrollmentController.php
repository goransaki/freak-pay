<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 30.6.2018.
 * Time: 13:00
 */

namespace api\modules\v1\controllers;


use api\components\ActiveController;
use common\models\User;

/**
 * Class EnrollmentController
 * @package api\modules\v1\controllers
 */
class EnrollmentController extends ActiveController
{
    public $modelClass = User::class;

    /**
     * @param $id
     * @param $type
     * @return array
     */
    public function actionStatus($id, $type)
    {
        return ['id' => $id, 'type' => $type];
    }
}