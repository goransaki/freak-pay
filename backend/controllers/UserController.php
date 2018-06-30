<?php
/**
 * Created by IntelliJ IDEA.
 * User: jasminsuljic
 * Date: 30/06/2018
 * Time: 13:35
 */

namespace backend\controllers;


use yii\web\Controller;

class UserController extends Controller
{
    public function actionView($id)
    {
        return ['id' => $id];
    }

}