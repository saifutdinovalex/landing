<?php

namespace models\tariffs\discount;

use yii\base\Exception;

/**
 * Class DiscountAbstract was created for Tariff to make a discount, abstract class must be extended
 *
 * Usage:
 *
 * ```php
 * class NewTypeDiscount extends DiscountAbstract
 * {
 * }
 * ```
 */
abstract class DiscountAbstract
{
    protected $type_id = null;
    protected $value;
    protected $calculate_value = null;

    public function __construct($value = null)
    {
        $this->value = $value;
    }

    abstract public function calculate($tariff);

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
        return $this;
    }

    public function getCalculateValue()
    {
        return $this->calculate_value;
    }

    public function getValue()
    {
        if ($this->value === null)
            throw new Exception('Value is not set');
        return $this->value;
    }

    public function getTypeId()
    {
        return $this->type_id;
    }

    public static function className()
    {
        return get_called_class();
    }
}