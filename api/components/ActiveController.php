<?php

namespace api\components;

use api\extensions\CorsFilter;
use common\extensions\ActiveRecord;
use common\helpers\HttpHelper;
use common\helpers\TimeHelper;
use \Yii;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController as BaseController;
use yii\web\NotFoundHttpException;
use yii\web\Response;

abstract class ActiveController extends BaseController
{
    public $modelClass;

    public $findModel = null;

    public function actions()
    {
        return ArrayHelper::merge(
            parent::actions(), [
            'search' => [
                'class' => 'api\extensions\actions\Search',
                'modelClass' => $this->modelClass,
            ],
            'delete' => [
                'class' => 'api\extensions\actions\Delete',
                'modelClass' => $this->modelClass
            ]
        ]);
    }

    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(
            [
                'corsFilter' => [
                    'class' => CorsFilter::className()
                ]
            ],
            parent::behaviors()
          /*  [
                'authenticator' => [
                    'class' => TokenAuth::className()
                ]
            ]*/
        );

        if (Yii::$app->request->getIsOptions()) {
            unset($behaviors['authenticator']);
        }

        return $behaviors;
    }

    protected function _wrapValidationErrors($errors)
    {
        return [
            'success' => false,
            'message' => 'validation error',
            'details' => $errors
        ];
    }

    protected function verbs()
    {
        $verbList = parent::verbs();

        foreach ($verbList as &$verbs) {
            if (!in_array('OPTIONS', $verbs)) {
                $verbs[] = 'OPTIONS';
            }
        }

        return $verbList;
    }

    protected function findModel($id)
    {
        if ($this->findModel !== null) {
            return call_user_func($this->findModel, $id, $this);
        }

        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;
        $model = $modelClass::findOne($id);

        if (empty($model)) {
            throw new NotFoundHttpException("Object not found: $id");
        }

        return $model;
    }

    protected function setResponseHeaderCache($expireTime, $eTag, $lastModified)
    {
        Yii::$app->response->headers->add('Expires', gmdate('D, j M Y H:i:s T', time() + $expireTime));
        Yii::$app->response->headers->add('ETag', $eTag);
        Yii::$app->response->headers->add('Cache-Control', "max-age={$expireTime}, must-revalidate");
        Yii::$app->response->headers->add('Last-Modified', gmdate('D, j M Y H:i:s', $lastModified) . ' GMT');
    }

    protected function isModified($eTag, $modifyTime)
    {

        $eTags = Yii::$app->request->getETags();

        if (in_array($eTag, $eTags)) {
            return false;
        }

        return !(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) &&
            strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $modifyTime);
    }

    protected function renderImageResponse($eTag, $lastModified, $mimeType, $storageKey)
    {
        $expireTime = TimeHelper::YEAR;
        $this->setResponseHeaderCache($expireTime, $eTag, $lastModified);

        if (!$this->isModified($eTag, $lastModified)) {
            Yii::$app->response->statusCode = HttpHelper::HTTP_NOT_MODIFIED;

            return '';
        }

        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', $mimeType);

        return Yii::$app->resourceManager->getFileData($storageKey);
    }

}