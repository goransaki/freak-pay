<?php
/**
 * ImageViewAction.php
 *
 ** Author: Goran Sarenac
 * Date: 21-Dec-15
 * Time: 22:17
 */

namespace api\extensions\actions;

use common\components\image\ImageSpecification;
use yii\db\ActiveRecord;
use yii\rest\ViewAction;

class ImageSpecViewAction extends ViewAction
{
    public function run($id, $spec = ImageSpecification::THUMB_LARGE)
    {
        /** @var ActiveRecord $model */
        $model = $this->findModel($id);

        if ($model->hasProperty('imageSpec')) {
            $model->imageSpec = $spec;
        }

        return $model;
    }
}