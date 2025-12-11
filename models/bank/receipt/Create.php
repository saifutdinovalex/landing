<?php

namespace landing\models\bank\receipt;

class Create
{
    public function __construct(private ?IInvoice $invoice = null)
    {
        if ($this->check()) {
            $this->build();
        }
    }

    /**
     * @return array
     */
    public function getResult(): array
    {
        return $this->result;
    }

    /**
     * @return bool
     */
    private function check(): bool
    {
        if ($this->invoice->getAmount() > 0) {
            return true;
        }

        if ($this->invoice->getEmail() || $this->invoice->getPhone()) {
            return true;
        }
        return false;
    }

    /**
     * @var array
     */
    private array $result = [];

    /**
     * @return void
     */
    private function build(): void
    {
        $object = new Read;
        $this->result = $object->setEmail($this->invoice->getEmail())
            ->setPhone($this->invoice->getPhone())
            ->setDescription($this->invoice->getDescription())
            ->setAmount($this->invoice->getAmount())
            ->build()
            ->getResult();
    }
}