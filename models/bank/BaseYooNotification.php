<?php

declare(strict_types=1);

namespace models\bank;

use helpers\YooError;
use helpers\YooKassaFactory;
use Yii;
use YooKassa\Model\NotificationEventType;

class BaseYooNotification extends YooKassaFactory
{
    protected object $invoice_save;
    protected string $invoice_model;
    protected string $socet_name;

    /**
     * {@inheritdoc}
     */
    public function build(): self
    {
        $notificationObject = $this->notificationObject;

        if (!$this->check($notificationObject)) {
            return $this;
        }

        $object = $this->invoice_save;
        $object->setId($this->invoice_id);
        $object->setStatusYookassa($this->payment_response->getStatus());
        $object->setPaymentId($this->payment_response->getId());

        if ($notificationObject->getEvent() === NotificationEventType::PAYMENT_CANCELED) {
            $object->setYookassaCancellationDetails(
                $this->payment_response->getCancellationDetails()->getReason()
            );
        } else {
            $object->setStatus((int) $this->payment_response->getPaid());
        }

        $object->build();
        $result = $object->getResult();

        if ($result !== true) {
            $this->errors = $object->getErrors();

            return $this;
        }

        $this->result = true;

        return $this;
    }

    /**
     * Check invoice existence
     */
    protected function check(object $notificationObject): bool
    {
        $object = $this->invoice_model;
        $model = $object::findOne($this->invoice_id);

        if ($model === null) {
            $this->errors = 'Not exist model invoice for invoice_id = ' . $this->invoice_id;

            return false;
        }

        if ($model['token_payment'] !== $this->hash) {
            $this->errors = 'Is not exist token_payment = ' . $this->hash;

            return false;
        }

        if ((int) $this->payment_response->getAmount()->getValue() !== (int) $model->money) {
            $this->errors = 'Amount is not real is = ' . $this->payment_response->getAmount()->getValue();

            return false;
        }

        if ($this->payment_response->getAmount()->getCurrency() !== 'RUB') {
            $this->errors = 'Currency is not RUB is = ' . $this->payment_response->getAmount()->getCurrency();

            return false;
        }

        return true;
    }
}