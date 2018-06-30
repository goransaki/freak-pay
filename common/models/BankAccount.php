<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bank_account".
 *
 * @property int $id
 * @property int $user_id
 * @property string $token_id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 * @property SecureData $token
 */
class BankAccount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank_account';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'token_id', 'created_at', 'updated_at'], 'required'],
            [['user_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['token_id'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 30],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['token_id'], 'exist', 'skipOnError' => true, 'targetClass' => SecureData::className(), 'targetAttribute' => ['token_id' => 'token']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token_id' => 'Token ID',
            'name' => 'Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
    public function getToken()
    {
        return $this->hasOne(SecureData::className(), ['token' => 'token_id']);
    }
}
