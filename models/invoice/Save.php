<?php

namespace models\invoice;

use Yii;
use models\ar\ArInvoiceUmarov;
use models\ABaseObject;
use models\invoice\money\Read as ReadMoney;

class Save extends ABaseObject
{
    protected $data;

    public function __call($name, $arguments)
    {
        $name = $this->getNameCallAttribute($name);
        $this->data[$name] = $arguments[0];
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
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
     * create invoice
     * @return object
     */
    protected function createInvoice()
    {
        $model = new ArInvoiceUmarov();
        $model->created_at = date('Y-m-d H:i:s');
        $model->money = $this->getMoney();
        $model->token_payment = \Yii::$app->security->generateRandomString(32);
        return $model;
    }

    /**
     * create date success paid
     * @param  object $model
     * @return object
     */
    protected function savePaidYoo($model)
    {
         if (isset($this->data['status']) && (int)$this->data['status'] == 1 && !$model['paid_at']) {
            $model->paid_at = date('Y-m-d H:i:s');
            $model->yookassa_cancellation_details = NULL;
        }
        return $model;
    }

    /**
     * получить стоимость тарифа
     * @return float
     */
    protected function getMoney()
    {
        $object = new ReadMoney($this->data['type_id']);
        $object->setPromoId($this->data['promo_id']??0);
        $object->setTypeId($this->data['type_id']);
        $object->build();
        return $object->getResult();
    }
}