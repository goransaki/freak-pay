<?php

namespace common\widgets;

use Yii;

class GridView extends \kartik\grid\GridView
{
    public $options = ['class' => 'm_datatable m-datatable m-datatable--default m-datatable--loaded loader-container'];
    public $tableOptions = ['class' => ''];
    public $layout = '{items}<div class="col-md-6 m-datatable__pager m-datatable--paging-loaded">{pager}</div><div class="col-md-6 table-summary">{summary}</div>';
    public $pager = ['class' => 'backend\components\extensions\LinkPager'];

    public function __construct(array $config = [])
    {
        $this->emptyText = Yii::t('app', 'No results found.');
        $this->summary = Yii::t('app', "Showing") . " <span>{begin}</span> - <span>{end}</span> " . Yii::t(
                "app",
                "of total"
            ) . " <span>{totalCount}</span> " . Yii::t("app", "results.") . "";

        parent::__construct($config);
    }


}