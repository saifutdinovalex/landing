<?php

namespace ar;

use Yii;

/**
 * This is the model class for table "invoice_umarov".
 *
 * @property int $id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property int $type_id
 * @property string $money
 * @property int $status
 * @property string $created_at
 * @property int $is_deleted
 * @property string $payment_id
 * @property string $paid_at
 * @property string $status_yookassa
 * @property string $yookassa_cancellation_details
 * @property string $token_payment
 */
class ArInvoiceUmarov extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice_umarov';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_id', 'status', 'is_deleted'], 'integer'],
            [['money'], 'number'],
            [['created_at', 'paid_at'], 'safe'],
            [['name', 'surname', 'email', 'payment_id', 'status_yookassa', 'yookassa_cancellation_details'], 'string', 'max' => 255],
            [['token_payment'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'surname' => 'Surname',
            'email' => 'Email',
            'type_id' => 'Type ID',
            'money' => 'Money',
            'status' => 'Status',
            'created_at' => 'Created At',
            'is_deleted' => 'Is Deleted',
            'payment_id' => 'Payment ID',
            'paid_at' => 'Paid At',
            'status_yookassa' => 'Status Yookassa',
            'yookassa_cancellation_details' => 'Yookassa Cancellation Details',
            'token_payment' => 'Token Payment',
        ];
    }

    public function getUtmData()
    {
        return $this->hasOne(ArUtmUmarov::class, ['invoice_id' => 'id']);
    }
}
