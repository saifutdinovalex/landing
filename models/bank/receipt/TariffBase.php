<?php

namespace landing\models\bank\receipt;

use Yii;
use landing\models\ABaseObject;
use landing\models\invoice\Config;

class Tariff extends ABaseObject
{
    protected string $email;
    protected string $phone;
    protected int $tariff_id;
    protected float $amount;

    /**
     * Email 
     * @param string $value
     */
    public function setEmail($value): self
    {
        $this->email = $value;
        return $this;
    }

    /**
     * Phone
     * @param string $value
     */
    public function setPhone($value): self
    {
        $this->phone = $value;
        return $this;
    }

    /**
     * Tariff
     * @param int $value
     */ 
    public function setTariff($value): self
    {
        $this->tariff_id = $value;
        return $this;
    }

    /**
     * Amount
     * @param float $value
     */
    public function setAmount($value): self
    {
        $this->amount = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build(): self
    {
        $object = new Read;
        $object->setEmail($this->email);
        $object->setPhone($this->phone);
        $object->setAmount($this->amount);
        $object->setDescription($this->getDescription());
        $object->build();
        $this->result = $object->getResult();
        return $this;
    }

    /**
     * @return string 
     */
    protected function getDescription(): string
    {
        $tariff = Config::getById($this->tariff_id);
        return $tariff['name'];
    }
}