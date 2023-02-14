<?php
namespace models\invoice;

class Type
{
    const STANDART = 1;
    const PREMIUM = 2;

    /**
     * {@inheritdoc}
     * @return array
     */
    public static function getAll()
    {
        return [
            static::STANDART,
            static::PREMIUM,
        ];
    }

    /**
     * {@inheritdoc}
     * @return array
     */
    public static function getNameAll()
    {
        return [
            static::STANDART => 'standart',
            static::PREMIUM => 'premium',
        ];
    }
    /**
     * ID тарифа
     * @param  string $name
     * @return int
     */
    public static function getName($name)
    {
        $statuses = static::getNameAll();
        return array_search($name, $statuses);
    }
}