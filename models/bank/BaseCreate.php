<?php

namespace models\bank;

use Yii;
use AbstractModelBase;
use helpers\YooError;
use response\Response;
use yii\helpers\ArrayHelper;

class BaseCreate extends AbstractModelBase
{
    public $invoice_id;

    protected $amount;
    protected $confirmation_token;
    protected $invoice;
    protected $payment_response;
    protected $errors_cancel;
    protected $hash;
    protected $url_success;
    protected $type_service;
    protected $invoice_model;
    protected $invoice_save;
    protected $get_receipt;
    protected $litter_invoice;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['invoice_id'],'required'],
            ['invoice_id', 'integer'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (parent::execute()) {
            $this->initData();
            $this->paymentCreate();
            $this->setDataResponse(['confirmation_token' => $this->confirmation_token]);
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    protected function initData()
    {
        $model = $this->invoice_model;
        $this->invoice = $model::find()->andWhere(['id' => $this->invoice_id, 'is_deleted' => 0, 'status' => 0])->one();

        if (!$this->invoice) {
            $this->addError('error', 'Not Exists');
            $this->sendError();
        }
        $this->amount = $this->invoice->money;
        $this->hash = $this->invoice->token_payment;
    }

    /**
     * создать платеж в юмани
     */
    protected function paymentCreate()
    {
        try {
            $client = Yii::$app->yookassa->getBase();
            $idempotenceKey = uniqid('', true);
            $response = $client->createPayment(
                [
                    'amount' => [
                        'value' => (string)$this->amount,
                        'currency' => 'RUB',
                    ],
                    'confirmation' => array(
                        'type' => 'embedded',
                        'locale' => 'ru_RU',
                        'return_url' => $this->url_success,
                    ),
                    'capture' => true,
                    'description' => 'Счет № '.$this->litter_invoice.$this->invoice_id,
                    'metadata' => [
                        'invoice_id' => $this->invoice_id,
                        'hash' => $this->invoice->token_payment,
                        'type_service' => $this->type_service,
                    ],   
                    'receipt' => $this->getReceipt(),              
                ],
                $idempotenceKey
            );
            $this->payment_response = $response;

            if ($this->payment_response) {
                $this->getErrorCancel();   
                $this->updateInvoice();    
            }
        } catch (\Exception $e) {
            $this->addError('error', \Yii::t('error', 'There was a payment error. Contact support chat'));
            $this->sendError();
        }
        
        if ($this->errors_cancel) {
            $this->sendError();
        }
    }

    /**
     * обновить данные в invoice
     */
    protected function updateInvoice()
    {
        $object = $this->invoice_save;
        $object->setId($this->invoice_id);
        $object->setStatusYookassa($this->payment_response->getStatus());
        $object->setPaymentId($this->payment_response->getId());

        if ($this->errors_cancel) {
            $object->setYookassaCancellationDetails($this->errors_cancel->getReason());    
        } 
        
        $object->build();
        $result = $object->getResult();
        
        if ($result !== true) {
            $this->addError('error', $object->getErrors());
            $this->sendError();
        }
    }

    /**
     * получить описание ошибки и токен
     */
    protected function getErrorCancel()
    {
        $this->errors_cancel = $this->payment_response->getCancellationDetails();

        if ($this->errors_cancel) {
            $object = new YooError($this->errors_cancel->getReason());
            $object->build();
            $error = $object->getResult();

            if (!$object->isFatal()) {
                $this->setCode(449);
            }
            $this->addError('error', $error);
        } else {
            if (!empty($this->payment_response->confirmation)) {
                $this->confirmation_token = $this->payment_response->getConfirmation()->getConfirmationToken();
            }
        }
    }

    /**
     * отправить данные для налога
     * @return [type] [description]
     */
    protected function getReceipt()
    {
        $object = $this->get_receipt;
        $object->setEmail($this->invoice->email);
        $object->setPhone('');
        $object->setAmount($this->invoice->money);
        $object->build();
        return $object->getResult();
    }

    /**
     * указываем урл для успшеной оплаты
     * @param string $value
     */     
    public function setUrlSuccess($value)
    {
        $this->url_success = $value;
        return $this;
    }

    /**
     * рзаные типы лендингов
     * @param string $value
     */
    public function setTypeService($value)
    {
        $this->type_service = $value;
        return $this;
    }

    /**
     * добавляем букву к номеру счета
     * @param string $value 
     */
    public function setLitterInvoice($value)
    {
        $this->litter_invoice = $value;
        return $this;
    }
}
