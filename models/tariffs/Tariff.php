<?php
namespace models\tariffs;


use models\invoice\Config;
use models\tariffs\discount\DiscountAbstract;

/**
 * Class Tariff
 *
 * Usage:
 *
 * ```php
 * $tariff = new Tariff(Config::TARIFF_EXPERT);
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
    protected $config;
    protected $count_month;
    protected $count_month_paid = null;
    protected $count_user;
    protected $cost;
    protected $total_cost;

    public function __construct($name)
    {
        $this->config = Config::getTariffConfig($name);
    }

    public function applyDiscount(DiscountAbstract $discount)
    {
        $discount->calculate($this);
        return $this;
    }

    public function calculate()
    {
        $this->total_cost = $this->cost = $this->config['price'];
        return $this;
    }

    public function setCountUser($count)
    {
        $this->count_user = $count;
        return $this;
    }

    public function setCountMonth($count)
    {
        $this->count_month = $count;
        return $this;
    }

    public function setCountMonthPaid($count)
    {
        $this->count_month_paid = $count;
        return $this;
    }

    public function setTotalCost($cost)
    {
        $this->total_cost = ($cost < 0) ? 0 : $cost;
        return $this;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function getTotalCost()
    {
        return $this->total_cost;
    }

    public function getCountMonth()
    {
        return $this->count_month;
    }

    public function getCountMonthPaid()
    {
        return $this->count_month_paid ?: $this->count_month;
    }

    public function getName()
    {
        return $this->config['name'];
    }

    public function getId()
    {
        return $this->config['id'];
    }
}