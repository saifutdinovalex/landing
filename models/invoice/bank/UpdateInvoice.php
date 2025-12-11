<?php

namespace app\models\invoice\bank;

class UpdateInvoice
{
    public function __construct(
                                    private IInvoice $invoice,
                                    private $payment_response = null
                                )
    {
        if ($this->payment_response && $this->invoice) {
            $this->build();
        }
    }

    /**
     * @var bool
     */
    private bool $result = false;

    /**
     * @return bool
     */
    public function getResult(): bool
    {
        return $this->result;
    }

    private function build(): void
    {
        $this->invoice->status_yookassa = $this->payment_response->getStatus();
        $this->invoice->payment_id = $this->payment_response->getId();
        $this->invoice->yookassa_cancellation_details = $this->payment_response->getError()
        $this->result = $this->invoice->save();
    }
}