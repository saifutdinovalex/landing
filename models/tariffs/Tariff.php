<?php

declare(strict_types=1);

namespace models\tariffs;

use models\invoice\Config;
use models\tariffs\discount\DiscountAbstract;

/**
 * Class Tariff
 *
 * Usage:
 *
 * ```php
 * $tariff = new Tariff(Config::TARIFF);
 * $tariff
 *      ->setCountMonth(12)
 *      ->setCountUser(1)
 *      ->calculate()
 *      ->applyDiscount(new DiscountPercent(10));
 *
 * $total = $tariff->getTotalCost();
 * ```
 */
class Tariff
{
    protected array $config;
    protected ?int $count_month = null;
    protected ?int $count_month_paid = null;
    protected ?int $count_user = null;
    protected ?float $cost = null;
    protected ?float $total_cost = null;

    public function __construct(string $name)
    {
        $this->config = Config::getTariffConfig($name);
    }

    public function applyDiscount(DiscountAbstract $discount): self
    {
        $discount->calculate($this);

        return $this;
    }

    public function calculate(): self
    {
        $this->total_cost = $this->cost = (float) $this->config['price'];

        return $this;
    }

    public function setCountUser(int $count): self
    {
        $this->count_user = $count;

        return $this;
    }

    public function setCountMonth(int $count): self
    {
        $this->count_month = $count;

        return $this;
    }

    public function setCountMonthPaid(?int $count): self
    {
        $this->count_month_paid = $count;

        return $this;
    }

    public function setTotalCost(float $cost): self
    {
        $this->total_cost = $cost < 0 ? 0.0 : $cost;

        return $this;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function getTotalCost(): ?float
    {
        return $this->total_cost;
    }

    public function getCountMonth(): ?int
    {
        return $this->count_month;
    }

    public function getCountMonthPaid(): ?int
    {
        return $this->count_month_paid ?: $this->count_month;
    }

    public function getName(): string
    {
        return $this->config['name'];
    }

    public function getId(): int
    {
        return $this->config['id'];
    }
}