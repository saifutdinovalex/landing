<?php

declare(strict_types=1);

namespace models\invoice;

use models\ABaseObject;
use models\ar\ArInvoiceUmarov;
use models\invoice\money\Read as ReadMoney;
use Yii;

class Save extends ABaseObject
{
    protected array $data = [];

    public function __call(string $name, array $arguments): self
    {
        $name = $this->getNameCallAttribute($name);
        $this->data[$name] = $arguments[0];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build(): self
    {
        if (isset($this->data['id'])) {
            $model = ArInvoiceUmarov::findOne($this->data['id']);
        } else {
            $model = $this->createInvoice();
        }

        foreach ($this->data as $attribute => $value) {
            $model->$attribute = $value;
        }

        $model = $this->savePaidYoo($model);

        if (!$model->save()) {
            $this->errors = $model->getErrors();
        } else {
            $this->model_ar = $model;
            $this->result = true;
            $this->afterBuild();
        }

        return $this;
    }

    /**
     * Create invoice
     */
    protected function createInvoice(): ArInvoiceUmarov
    {
        $model = new ArInvoiceUmarov();
        $model->created_at = date('Y-m-d H:i:s');
        $model->money = $this->getMoney();
        $model->token_payment = Yii::$app->security->generateRandomString(32);

        return $model;
    }

    /**
     * Create date success paid
     */
    protected function savePaidYoo(ArInvoiceUmarov $model): ArInvoiceUmarov
    {
        if (
            isset($this->data['status'])
            && (int) $this->data['status'] === 1
            && !$model['paid_at']
        ) {
            $model->paid_at = date('Y-m-d H:i:s');
            $model->yookassa_cancellation_details = null;
        }

        return $model;
    }

    /**
     * Get tariff cost
     */
    protected function getMoney(): float
    {
        $object = new ReadMoney($this->data['type_id']);
        $object->setPromoId($this->data['promo_id'] ?? 0);
        $object->setTypeId($this->data['type_id']);
        $object->build();

        return (float) $object->getResult();
    }
}