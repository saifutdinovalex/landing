<?php

declare(strict_types=1);

namespace models\invoice;

use ar\ArInvoiceUmarov;
use helpers\Hash;
use helpers\YooError;
use models\AbstractModel;
use Yii;

class Read extends AbstractModel
{
    public string $invoice_id;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['invoice_id', 'required'],
            ['invoice_id', 'trim'],
            ['invoice_id', 'string'],
            [
                'invoice_id',
                'filter',
                'filter' => function (string $value): ?string {
                    $result = explode('__', $value);

                    if (isset($result[0])) {
                        $model = ArInvoiceUmarov::find()
                            ->where('id=:id')
                            ->addParams([':id' => $result[0]])
                            ->one();

                        if ($model !== null) {
                            $hash = Hash::get($result[0], $model->token_payment);

                            if ($value === $hash) {
                                return $result[0];
                            }
                        }
                    }

                    $this->addError('error', 'Not exists invoice');

                    return null;
                },
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): bool
    {
        if (parent::execute()) {
            $this->checkStatus();

            return true;
        }

        return false;
    }

    /**
     * Check payment status
     */
    protected function checkStatus(): bool
    {
        $model = ArInvoiceUmarov::find()->where(['id' => $this->invoice_id])->one();

        if ($model === null) {
            $this->addError('error', Yii::t('error', 'Not exist'));
            $this->sendError();
        }

        if (
            $model['status'] == 0
            && $model['status_yookassa'] === 'canceled'
            && $model['yookassa_cancellation_details'] !== null
            && $model['yookassa_cancellation_details'] !== 'canceled_by_merchant'
        ) {
            $object = new YooError($model['yookassa_cancellation_details']);
            $object->build();
            $this->addError('error', $object->getResult());
            $this->sendError();
        }

        if ($model['status'] == 0 && $model['status_yookassa'] === 'pending') {
            $this->setCode(449);
            $this->addError('error', Yii::t('error', 'Waiting'));
            $this->sendError();
        }

        if ($model['status'] == 0) {
            $this->addError('error', Yii::t('error', 'Not paid'));
            $this->sendError();
        }

        if ($model['status'] == 1) {
            return true;
        }

        return false;
    }
}