<?php

namespace models\bank\receipt;

use Yii;
use models\invoice\Config;

class Tariff extends TariffBase
{
    /**
     * описание тарифа
     * @return string
     */
    protected function getDescription()
    {
        $tariff = Config::getById($this->tariff_id);
        return 'Доступ к материалам, в рамках тарифа "'.$tariff['name'].'"';
    }
}