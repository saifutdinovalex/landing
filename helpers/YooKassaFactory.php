<?php

declare(strict_types=1);

namespace helpers;

use ABaseObject;
use Yii;

class YooKassaFactory extends ABaseObject
{
    protected ?int $invoice_id = null;
    protected mixed $payment_response = null;
    protected ?int $user_id = null;
    protected string $status_text = '';
    protected ?string $hash = null;
    protected mixed $notificationObject = null;
    protected ?array $data = null;

    /**
     * Set payment response data
     *
     * @param mixed $value Payment response object or array
     * @return self
     */
    public function setPaymentResponse(mixed $value): self
    {
        $this->payment_response = $value;

        return $this;
    }

    /**
     * Set invoice identifier
     *
     * @param int $value Invoice ID
     * @return self
     */
    public function setInvoiceId(int $value): self
    {
        $this->invoice_id = $value;

        return $this;
    }

    /**
     * Set hash for verification
     *
     * @param string $value Security hash
     * @return self
     */
    public function setHash(string $value): self
    {
        $this->hash = $value;

        return $this;
    }

    /**
     * Set notification object from YooKassa
     *
     * @param mixed $value YooKassa notification object
     * @return self
     */
    public function setNotificationObject(mixed $value): self
    {
        $this->notificationObject = $value;

        return $this;
    }

    /**
     * Set additional data
     *
     * @param array $value Additional data array
     * @return self
     */
    public function setData(array $value): self
    {
        $this->data = $value;

        return $this;
    }
}