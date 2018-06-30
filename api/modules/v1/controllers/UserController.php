<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 30.6.2018.
 * Time: 13:03
 */

namespace api\modules\v1\controllers;


use api\components\ActiveController;
use common\models\User;

/**
 * Class UserController
 * @package api\modules\v1\controllers
 */
class UserController extends ActiveController
{
    public $modelClass = User::class;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view']);
        return $actions;
    }

    /**
     * @param $id
     * @return array
     */
    public function actionView($id)
    {
        return ['id' => $id];
    }
}