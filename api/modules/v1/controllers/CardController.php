<?php

namespace api\modules\v1\controllers;
use common\models\User;

/**
 * Class CardController
 * @package api\modules\v1\controllers
 */
class CardControlle extends \api\components\ActiveController
{
    public $modelClass = User::class;

    /**
     * @return array
     */
    public function actionIndex()
    {
        return ['1' => 'test'];
    }

}