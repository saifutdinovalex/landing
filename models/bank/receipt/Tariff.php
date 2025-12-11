<?php

namespace landing\models\bank\receipt;

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
        return  Yii::t('error', 'Access to materials within the tariff').'"'.$tariff['name'].'"';
    }
}