<?php
/**
 * PrintImages.php
 *
 ** Author: Goran Sarenac
 * Date: 04-Mar-16
 * Time: 13:13
 */

namespace api\extensions\actions;

use common\components\image\ImageSpecification;
use common\extensions\ActiveRecord;
use common\models\Image;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PrintImages extends Action
{
    public $searchModelClass;
    public $searchResultKey = 'id';
    public $searchFormName = null;

    public $extraFieldsName = 'images';

    public $specAttribute = 'imageSpec';

    public $viewFile = '@common/views/print-images/print.php';

    public $relationName = 'orderedImages';

    public $spec = ImageSpecification::PRINT_PORTRAIT_SPEC;

    public function run()
    {
        $idString = Yii::$app->request->getQueryParam('ids', Yii::$app->request->getQueryParam('id', ''));

        $searchQuery = urldecode(Yii::$app->request->getQueryParam('query', ''));

        $modelIdArray = explode(',', $idString);


        if (!empty($searchQuery)) {
            $modelIdArray = $this->processSearchQuery($searchQuery);
        }


        if (empty($idString) && empty($searchQuery)) {
            throw new NotFoundHttpException();
        }

        $isAnnotated = Yii::$app->request->getQueryParam('annotated', false);

        $images = [];

        foreach ($modelIdArray as $modelId) {
            $modelImages = $this->getImages($modelId);
            foreach ($modelImages as $imageId => $imageUrl) {
                $images[$imageId] = $imageUrl;
            }
        }

        $processList = [];

        $imageIds = Yii::$app->request->getQueryParam('imageIds', null);

        $imageIdList = $imageIds !== null ? explode(',', $imageIds) : null;

        foreach ($images as $imageId => $imageUrl) {
            if ($imageIdList !== null && !in_array($imageId, $imageIdList)) {
                continue;
            }

            $processList[$imageId] = $imageUrl;
        }

        return $this->controller->render($this->viewFile, [
            'images' => $processList,
            'isAnnotated' => $isAnnotated
        ]);
    }

    protected function getImages($id)
    {
        /** @var ActiveRecord $model */
        $model = $this->findModel($id);

        Yii::$app->response->format = Response::FORMAT_HTML;

        $this->controller->layout = false;

        /** @var Image[] $images */
        $images = $model->getRelation($this->relationName)->all();

        return ArrayHelper::map($images, 'id', function(Image $model) {
            return $model->getPrintUrl();
        });
    }

    protected function processSearchQuery($searchQuery)
    {
        $searchParams = [];
        parse_str($searchQuery, $searchParams);

        $searchModelClass = $this->searchModelClass;
        if (empty($searchModelClass)) {
            return [];
        }

        /** @var ActiveRecord $searchModel */
        $searchModel = new $searchModelClass();

        $searchModel->load($searchParams, $this->searchFormName);

        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = $searchModel->search();
        $dataProvider->setPagination(false);

        $models = $dataProvider->getModels();

        $idArray = [];

        foreach ($models as $model) {
            $idArray[] = ArrayHelper::getValue($model, $this->searchResultKey);
        }

        return $idArray;
    }
}