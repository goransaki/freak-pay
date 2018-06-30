<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "secure_data".
 *
 * @property string $token
 * @property string $data
 * @property string $secure_vault_provider
 * @property string $created_at
 * @property string $updated_at
 * @property string $exp_date
 *
 * @property BankAccount[] $bankAccounts
 * @property Card[] $cards
 * @property CryptoCurrency[] $cryptoCurrencies
 */
class SecureData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'secure_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token', 'created_at', 'updated_at'], 'required'],
            [['data'], 'string'],
            [['created_at', 'updated_at', 'exp_date'], 'safe'],
            [['token'], 'string', 'max' => 36],
            [['secure_vault_provider'], 'string', 'max' => 500],
            [['token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'token' => 'Token',
            'data' => 'Data',
            'secure_vault_provider' => 'Secure Vault Provider',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'exp_date' => 'Exp Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBankAccounts()
    {
        return $this->hasMany(BankAccount::className(), ['token_id' => 'token']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCards()
    {
        return $this->hasMany(Card::className(), ['token_id' => 'token']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCryptoCurrencies()
    {
        return $this->hasMany(CryptoCurrency::className(), ['token_id' => 'token']);
    }
}
