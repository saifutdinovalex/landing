<?php

declare(strict_types=1);

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
    protected ?int $type_id = null;
    protected mixed $value;
    protected mixed $calculate_value = null;

    public function __construct(mixed $value = null)
    {
        $this->value = $value;
    }

    abstract public function calculate(mixed $tariff): mixed;

    public function setValue(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function setTypeId(int $type_id): self
    {
        $this->type_id = $type_id;

        return $this;
    }

    public function getCalculateValue(): mixed
    {
        return $this->calculate_value;
    }

    public function getValue(): mixed
    {
        if ($this->value === null) {
            throw new Exception('Value is not set');
        }

        return $this->value;
    }

    public function getTypeId(): ?int
    {
        return $this->type_id;
    }

    public static function className(): string
    {
        return static::class;
    }
}