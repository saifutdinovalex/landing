<?php

namespace helpers;

use Yii;
use ABaseObject;

class YooKassaFactory extends ABaseObject
{
    protected $invoice_id;
    protected $payment_response;
    protected $user_id;
    protected $status_text = '';
    protected $hash;
    protected $notificationObject;
    protected $data;
    
    /**
     * @param array $value
     */
    public function setPaymentResponse($value)
    {
        $this->payment_response = $value;
        return $this;
    }

    /**
     * @param int $value
     */
    public function setInvoiceId($value)
    {
        $this->invoice_id = $value;
        return $this;
    }

    /**
     * @param string $value
     */
    public function setHash($value)
    {
        $this->hash = $value;
        return $this;
    }

    /**
     * @param array $value
     */
    public function setNotificationObject($value)
    {
        $this->notificationObject = $value;
        return $this;
    }

    /**
     * @param array $value
     */
    public function setData($value)
    {
        $this->data = $value;
        return $this;
    }
}