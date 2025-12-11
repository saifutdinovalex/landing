<?php

declare(strict_types=1);

namespace landing\models\bank\receipt;

use landing\models\ABaseObject;

/**
 * Class Read for building receipt data according to 54-FZ
 * @see https://yookassa.ru/developers/payment-acceptance/scenario-extensions/54fz/parameters-values
 */
class Read extends ABaseObject
{
    private ?string $email = null;
    private ?string $phone = null;
    private ?string $description = null;
    private float $amount = 0;

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function setAmount(float $amount): self
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than 0');
        }
        
        $this->amount = $amount;
        return $this;
    }

    public function build(): self
    {        
        $this->result = [
            'customer' => [
                'email' => $this->email,
                'phone' => $this->phone,
            ],
            'tax_system_code' => 2,
            'items' => [
                [
                    'description' => $this->description,
                    'quantity' => 1.00,
                    'amount' => [
                        'value' => number_format($this->amount, 2, '.', ''),
                        'currency' => 'RUB',
                    ],
                    'vat_code' => 1,
                    'payment_subject' => 'service',
                    'payment_mode' => 'full_payment',
                ],
            ],            
        ];
        
        return $this;
    }
}