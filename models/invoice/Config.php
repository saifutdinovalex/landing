<?php
namespace models\invoice;

use models\BaseConfig;

class Config extends BaseConfig
{
    /**
     * список тарифов
     * @return array
     */
    public static function get()
    {
        return [
            'standart' => [
                'id' => Type::STANDART,
                'name' => \Yii::t('error', 'Базовый'),
                'name_eng' => 'standart',
                'price' => self::getPrice('standart'),
                'group_name_getcourse' => '',
            ],
            'premium' => [
                'id' => Type::PREMIUM,
                'name' => \Yii::t('error', 'Продвинутый'),
                'name_eng' => 'premium',
                'price' => self::getPrice('premium'),
                'group_name_getcourse' => '',
            ],
           
        ];
    }

    /**
     * получить информацию по тарифу
     * @param  int $id 
     * @return array|null     
     */
    public static function getById($id)
    {
        foreach (static::get() as $tariff) {
            if ($tariff['id'] == $id) {
                return $tariff;
            }
        }
        return null;
    }

    /**
     * получить информацию по тарифу                            
     * @param  string $name 
     * @return array
     */
    public static function getTariffConfig($name)
    {
        if (in_array($name, Type::getAll())) { // by id
            $config = static::get();
            foreach ($config as $item) {
                if ($item['id'] == $name) {
                    return $item;
                }
            }
        }
        return static::get()[$name]; // by name
    }
}