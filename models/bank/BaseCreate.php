<?php

namespace landing\models\bank;

use app\models\invoice\bank\UpdateInvoice;
use landing\models\bank\token\Create;
use landing\models\invoice\bank\InvoiceToken;
use Yii;
use landing\models\AbstractModelBase;
use yii\helpers\ArrayHelper;

class BaseCreate extends AbstractModelBase
{
    public int $invoice_id;
    protected ?string $confirmation_token = null;
    protected ?IInvoice $invoice = null;
    protected ?object $payment_response = null;

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return ArrayHelper::merge(parent::rules(), [
            [['invoice_id'], 'required'],
            ['invoice_id', 'integer'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): bool
    {
        if (parent::execute()) {
            $this->initData();
            $this->paymentCreate();
            $this->updateInvoice();
            $this->setDataResponse(['confirmation_token' => $this->confirmation_token]);
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function initData(): void
    {
        $this->invoice = (new InvoiceToken($this->invoice_id))->getResult();

        if (!$this->invoice) {
            $this->addError('error', \Yii::t('error', 'Not Exists'));
            $this->sendError();
        }
    }

    /**
     * создать платеж в юмани и получить токен
     */
    protected function paymentCreate(): void
    {
        $this->payment_response = new Create($this->invoice);
        $this->confirmation_token = $this->payment_response->getResult();
    }

    /**
     * обновить данные в invoice
     */
    protected function updateInvoice(): void
    {
        $object = new UpdateInvoice($this->invoice, $this->payment_response);
        $result = $object->getResult();

        if ($result !== true) {
            $this->addError('error', \Yii::t('error', 'Error save'));
            $this->sendError();
        }
    }
}