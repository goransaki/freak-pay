<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "orders".
 *
 * @property int $id
 * @property string $identifier
 * @property int $store_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $status
 *
 * @property OrderProduct[] $orderProducts
 * @property Store $store
 * @property Transaction[] $transactions
 */
class Orders extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'orders';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identifier', 'store_id', 'created_at', 'updated_at'], 'required'],
            [['store_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['identifier'], 'string', 'max' => 255],
            [['status'], 'string', 'max' => 200],
            [['store_id'], 'exist', 'skipOnError' => true, 'targetClass' => Store::className(), 'targetAttribute' => ['store_id' => 'id']],
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
            'store_id' => 'Store ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
        ];
    }

    /**
     * @return array
     */
    public function extraFields()
    {
        return [
            'products' => function () {
                return $this->getProducts()->all();
            },
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrderProducts()
    {
        return $this->hasMany(OrderProduct::className(), ['order_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::class, ['id' => 'product_id'])->via('orderProducts');
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
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['order_id' => 'id']);
    }
}
