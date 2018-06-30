<?php

namespace api\extensions\actions;

use common\models\ReportModel;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\helpers\Json;
use yii\rest\Action;

class Report extends Action
{
    /** @var callable */
    public $scopeCallback;

    /** @var string */
    public $formName = '';

    public $stringColumns = [];

    public function init()
    {
        parent::init();

        if (empty($this->modelClass)) {
            throw new Exception("reportModel must be defined.");
        }
    }


    public function run()
    {
        $params = Yii::$app->request->post();

        $scopeCallback = $this->scopeCallback;

        if (is_callable($scopeCallback)) {
            $params = $scopeCallback($params);
        }

        $reportModelClass = $this->modelClass;

        /** @var Model $searchModel */
        $searchModel = new $reportModelClass();
        $searchModel->load($params, $this->formName);

        $dataProvider = $searchModel->search();

        $customSort = $this->getCustomSort();
        if($customSort){
            $dataProvider->getSort()->defaultOrder = $customSort;
        }

        $reportModel = new ReportModel([
            'report_config' => [
                'dataProvider' => $dataProvider,
                'columns' => Json::decode(Yii::$app->request->post('columns', '[]')),
                'searchInfo' => [
                    'class' => $searchModel->className(),
                    'stringColumns' => $this->stringColumns
                ]
            ],
        ]);

        return $reportModel->getReportProgress();
    }

    private function getCustomSort(){
        $sortOrder = [];
        $customSortString = Yii::$app->request->getQueryParam('sort');
        if(empty($customSortString)){
            return null;
        }
        $sortAttributes = explode(',', $customSortString);

        foreach ($sortAttributes as $attribute) {
            $descending = false;
            if (strncmp($attribute, '-', 1) === 0) {
                $descending = true;
                $attribute = substr($attribute, 1);
            }

            $sortOrder[$attribute] = $descending ? SORT_DESC : SORT_ASC;
        }
        return $sortOrder;
    }
}