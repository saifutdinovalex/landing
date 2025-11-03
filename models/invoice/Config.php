<?php

declare(strict_types=1);

namespace models\invoice;

use models\BaseConfig;
use Yii;

class Config extends BaseConfig
{
    /**
     * Get tariffs list
     */
    public static function get(): array
    {
        return [
            'standart' => [
                'id' => Type::STANDART,
                'name' => Yii::t('error', 'Базовый'),
                'name_eng' => 'standart',
                'price' => self::getPrice('standart'),
                'group_name_getcourse' => '',
            ],
            'premium' => [
                'id' => Type::PREMIUM,
                'name' => Yii::t('error', 'Продвинутый'),
                'name_eng' => 'premium',
                'price' => self::getPrice('premium'),
                'group_name_getcourse' => '',
            ],
        ];
    }

    /**
     * Get tariff information by ID
     */
    public static function getById(int $id): ?array
    {
        foreach (static::get() as $tariff) {
            if ($tariff['id'] === $id) {
                return $tariff;
            }
        }

        return null;
    }

    /**
     * Get tariff configuration by name or ID
     */
    public static function getTariffConfig(string $name): ?array
    {
        if (in_array($name, Type::getAll(), true)) {
            $config = static::get();
            foreach ($config as $item) {
                if ($item['id'] === (int) $name) {
                    return $item;
                }
            }
        }

        return static::get()[$name] ?? null;
    }
}