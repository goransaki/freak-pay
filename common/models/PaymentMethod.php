<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_method".
 *
 * @property int $id
 * @property string $identifier
 * @property string $type
 * @property string $created_at
 * @property string $updated_at
 * @property int $user_id
 * @property int $sort_key
 * @property int $auth_enabled
 *
 * @property Transaction[] $transactions
 */
class PaymentMethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['identifier', 'created_at', 'updated_at', 'user_id'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id', 'sort_key', 'auth_enabled'], 'integer'],
            [['identifier'], 'string', 'max' => 500],
            [['type'], 'string', 'max' => 30],
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
            'type' => 'Type',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_id' => 'User ID',
            'sort_key' => 'Sort Key',
            'auth_enabled' => 'Auth Enabled',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['payment_method_id' => 'id']);
    }
}
