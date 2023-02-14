<?php

namespace models\invoice\money;

use Yii;
use models\ABaseObject;

class BaseRead extends ABaseObject
{
    protected $config;
    protected $tariff;
    protected $service_type;

    /**
     * @param string $value 
     */
    public function setServiceType($value)
    {
        $this->service_type = $value;
        return $this;
    }

    protected $type_id;

    /**
     * тип тарифа
     * @param int $value
     */
    public function setTypeId($value)
    {
        $this->type_id = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $config = $this->config;
        $money = $config::getTariffConfig($this->type_id);
        $this->result = $money['price'];
        return $this;
    }
}