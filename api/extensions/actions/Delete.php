<?php

namespace api\extensions\actions;

use common\behaviors\RecycleBinBehavior;
use common\extensions\ActiveRecord;
use common\helpers\HttpHelper;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ServerErrorHttpException;
use yii\rest\DeleteAction;


class Delete extends DeleteAction
{
    public $recycleBinAttribute = 'is_in_recycle_bin';
    public $allowDeleteMultiple = false;
    public $deleteMultiplePermission = null;

    public function run($id)
    {
        if (strpos($id, ',') === false) {
            Yii::$app->getResponse()->setStatusCode(HttpHelper::HTTP_OK);
            return [
                'deleteStatus' => [
                    $id => $this->deleteItem($id)
                ]
            ];
        }

        if (!$this->allowDeleteMultiple) {
            throw new BadRequestHttpException();
        }

        if ($this->deleteMultiplePermission !== null && !Yii::$app->user->can($this->deleteMultiplePermission)) {
            throw new BadRequestHttpException("You are not allowed to delete multiple items.");
        }

        Yii::$app->getResponse()->setStatusCode(HttpHelper::HTTP_OK);

        $ids = explode(',', $id);

        $deletedIds = [];

        foreach ($ids as $id) {
            $deletedIds[$id] = $this->deleteItem($id, false);
        }

        return [
            'deleteStatus' => $deletedIds
        ];
    }

    protected function deleteItem($id, $throwErrorOnFail = true)
    {
        try {
            $model = $this->findModel($id);
        } catch (\Exception $e) {
            if ($throwErrorOnFail) {
                throw $e;
            }

            return false;
        }

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if (!$model->hasAttribute($this->recycleBinAttribute)) {
            if ($model->delete() === false) {
                throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
            }

            return true;
        }

        /** @var RecycleBinBehavior|ActiveRecord $model */
        if (Yii::$app->request->get('isPermanent', false) == 1) {
            return $model->deletePermanently();
        }

        $model->delete();
        return true;
    }
}