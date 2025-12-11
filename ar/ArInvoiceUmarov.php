<?php

declare(strict_types=1);

namespace landing\ar;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\AttributeBehavior;
use yii\db\AcitveQuery;

/**
 * This is the model class for table "invoice_umarov".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $email
 * @property int $type_id
 * @property string $money
 * @property int $status
 * @property string|null $created_at
 * @property int $is_deleted
 * @property string|null $payment_id
 * @property string|null $paid_at
 * @property string|null $status_yookassa
 * @property string|null $yookassa_cancellation_details
 * @property string $token_payment
 * 
 * @property ArUtmUmarov|null $utmData Related UTM data
 */
class ArInvoiceUmarov extends ActiveRecord implements IInvoice
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'invoice_umarov';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['type_id', 'status', 'is_deleted'], 'integer'],
            [['money'], 'number'],
            [['created_at', 'paid_at'], 'safe'],
            [
                ['name', 'surname', 'email', 'payment_id', 'status_yookassa', 'yookassa_cancellation_details'],
                'string',
                'max' => 255,
            ],
            [
                ['token_payment'],
                'string',
                'max' => 32,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'date_create' => [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
                'value' => function () {
                    return date('Y-m-d H:i:s');
                }
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
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

    /**
     * Get related UTM data
     * 
     * Establishes one-to-one relationship with UTM tracking data
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getUtmData(): ActiveQuery
    {
        return $this->hasOne(ArUtmUmarov::class, ['invoice_id' => 'id']);
    }
}