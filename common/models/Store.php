<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store".
 *
 * @property int $id
 * @property string $identifier
 * @property string $name
 * @property string $latitude
 * @property string $longitude
 * @property string $address
 * @property string $created_at
 *
 * @property Orders[] $orders
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
            [['identifier', 'name', 'latitude', 'longitude', 'address', 'created_at'], 'required'],
            [['created_at'], 'safe'],
            [['identifier', 'name'], 'string', 'max' => 255],
            [['latitude', 'longitude'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 2000],
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
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'address' => 'Address',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrders()
    {
        return $this->hasMany(Orders::className(), ['store_id' => 'id']);
    }
}
