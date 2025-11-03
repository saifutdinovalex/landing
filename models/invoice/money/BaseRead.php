<?php

namespace models\invoice\money;

use models\ABaseObject;
use Yii;

class BaseRead extends ABaseObject
{
    protected object $config;
    protected ?array $tariff = null;
    protected string $service_type;
    protected int $type_id;

    public function setServiceType(string $value): self
    {
        $this->service_type = $value;

        return $this;
    }

    public function setTypeId(int $value): self
    {
        $this->type_id = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build(): self
    {
        $config = $this->config;
        $money = $config::getTariffConfig($this->type_id);
        $this->result = (float) $money['price'];

        return $this;
    }
}