<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property int $id
 * @property string $identifier
 * @property string $name
 *
 * @property Transaction[] $transactions
 * @property TransactionItem[] $transactionItems
 */
class Store extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identifier', 'name'], 'required'],
            [['identifier', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identifier' => 'Identifier',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['store_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionItems()
    {
        return $this->hasMany(TransactionItem::className(), ['store_id' => 'id']);
    }
}
