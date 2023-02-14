<?php

namespace models\bank\receipt;

use Yii;
use models\ABaseObject;
use models\invoice\Config;

class Tariff extends ABaseObject
{
    protected $email;
    protected $phone;
    protected $tariff_id;
    protected $amount;

    /**
     * Email 
     * @param string $value
     */
    public function setEmail($value)
    {
        $this->email = $value;
        return $this;
    }

    /**
     * Phone
     * @param string $value
     */
    public function setPhone($value)
    {
        $this->phone = $value;
        return $this;
    }

    /**
     * Tariff
     * @param int $value
     */ 
    public function setTariff($value)
    {
        $this->tariff_id = $value;
        return $this;
    }

    /**
     * Amount
     * @param float $value
     */
    public function setAmount($value)
    {
        $this->amount = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
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
    protected function getDescription()
    {
        $tariff = Config::getById($this->tariff_id);
        return $tariff['name'];
    }
}