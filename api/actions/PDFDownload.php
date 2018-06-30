<?php

namespace api\extensions\actions;

use common\extensions\ActiveRecord;
use common\models\Image;
use common\models\PdfModel;
use Yii;
use yii\helpers\Json;
use yii\rest\Action;

class PDFDownload extends Action
{
    public $modelRelation = 'images';

    public function run($id)
    {
        $reportProgressId = Yii::$app->request->post('reportProgressId', null);
        $images = Yii::$app->request->post('images', null);

        if (empty($images)) {
            /** @var ActiveRecord $model */
            $model = $this->findModel($id);
            $imageArray = $model->{$this->modelRelation};
        } else {
            $images = Json::decode($images);
            $imageArray = Image::findAll(['id' => $images]);
        }

        $pdfModel = new PdfModel([
            'images' => $imageArray,
            'report_progress_id' => $reportProgressId,
        ]);

        return $pdfModel->getReportProgress();
    }
}