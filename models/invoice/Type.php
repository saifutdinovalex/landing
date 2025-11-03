<?php

declare(strict_types=1);

namespace models\invoice;

class Type
{
    public const STANDART = 1;
    public const PREMIUM = 2;

    /**
     * {@inheritdoc}
     */
    public static function getAll(): array
    {
        return [
            static::STANDART,
            static::PREMIUM,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function getNameAll(): array
    {
        return [
            static::STANDART => 'standart',
            static::PREMIUM => 'premium',
        ];
    }

    /**
     * Get tariff ID by name
     */
    public static function getName(string $name): int
    {
        $statuses = static::getNameAll();

        return array_search($name, $statuses, true);
    }
}