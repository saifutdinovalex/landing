<?php

namespace landing\models\invoice\bank;

use landing\ar\ArInvoiceUmarov;

class InvoiceToken
{
    public function __construct(private int $id)
    {
        $this->result = ArInvoiceUmarov::find()
                        ->where([
                                'id' => $this->id,
                                'is_deleted' => 0,
                                'status' => 0
                        ])
                        ->one();
    }

    private ?ArInvoiceUmarov $result;

    public function getResult(): ?ArInvoiceUmarov
    {
        return $this->result;
    }
}