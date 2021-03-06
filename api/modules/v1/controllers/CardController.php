<?php

namespace api\modules\v1\controllers;
use common\models\User;

/**
 * Class CardController
 * @package api\modules\v1\controllers
 */
class CardController extends \api\components\ActiveController
{
    public $modelClass = User::class;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    /**
     * @return array
     */
    public function actionIndex()
    {
        return ['1' => 'test'];
    }

}