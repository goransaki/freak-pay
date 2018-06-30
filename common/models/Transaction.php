<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property int $id
 * @property string $reference
 * @property int $store_id
 * @property int $user_id
 * @property double $total_price
 * @property int $used_points
 * @property int $got_points
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Store $store
 * @property User $user
 * @property TransactionItem[] $transactionItems
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reference', 'created_at', 'updated_at'], 'required'],
            [['store_id', 'user_id', 'used_points', 'got_points'], 'integer'],
            [['total_price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['reference'], 'string', 'max' => 255],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reference' => 'Reference',
            'store_id' => 'Store ID',
            'user_id' => 'User ID',
            'total_price' => 'Total Price',
            'used_points' => 'Used Points',
            'got_points' => 'Got Points',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStore()
    {
        return $this->hasOne(Store::className(), ['id' => 'store_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactionItems()
    {
        return $this->hasMany(TransactionItem::className(), ['transaction_id' => 'id']);
    }
}
