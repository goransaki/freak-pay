<?php
namespace common\extensions;


use common\helpers\ArrayHelper;

class ActiveRecordQuery extends \yii\db\ActiveQuery
{
    public $orderColumn = 'order';

    protected $_areDeletedExcluded = false;
    public $isDeletedColumn = 'is_deleted';

    public function hasSoftDelete()
    {
        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;

        return $modelClass::hasSoftDelete();
    }

    public function sortByOrder()
    {
        if (!$this->hasOrderColumn()) {
            return $this;
        }
        $modelClass = $this->modelClass;
        return $this->addOrderBy($modelClass::tableName() . '.' . $this->orderColumn);
    }

    protected function hasOrderColumn()
    {
        $modelClass = $this->modelClass;
        $columnNames = $modelClass::getTableSchema()->getColumnNames();
        return in_array($this->orderColumn, $columnNames);
    }

    public function excludeDeleted()
    {
        if (!$this->hasSoftDelete() || $this->_areDeletedExcluded) {
            return $this;
        }

        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        $this->onCondition([$modelClass::tableName() . '.' . $this->isDeletedColumn => 0]);
        $this->_areDeletedExcluded = true;

        return $this;
    }

    public function includeDeleted()
    {
        if (!$this->hasSoftDelete() || !$this->_areDeletedExcluded) {
            return $this;
        }

        $this->onCondition(null);
        $this->_areDeletedExcluded = false;

        return $this;
    }

    public function listAll($keyColumn, $valueColumn, $group = null)
    {
        return ArrayHelper::map($this->all(), $keyColumn, $valueColumn, $group);
    }
}