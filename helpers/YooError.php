<?php

namespace helpers;

use Yii;
use ABaseObject;

class YooError extends ABaseObject
{
    protected $data;
    
    public function __construct($name = '')
    {
        $this->data = $name;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $this->result = $this->data?$this->getText()[$this->data]:$this->getText();
        return $this;
    }

    /**
     * список статусов юмани
     * @return array 
     */
    protected function getText()
    {
        return [
            '3d_secure_failed' => 'Не пройдена аутентификация по 3-D Secure',
            'call_issuer' => 'Оплата данным платежным средством отклонена по неизвестным причинам',
            'canceled_by_merchant' => 'Платеж возвращен при холдировании',
            'card_expired' => 'Истек срок действия банковской карты',
            'country_forbidden' => 'Нельзя заплатить банковской картой, выпущенной в этой стране',
            'deal_expired' => 'закончился срок жизни сделки',
            'expired_on_capture' => 'Истек срок списания оплаты',
            'expired_on_confirmation' => 'Истек срок оплаты',
            'fraud_suspected' => 'Платеж заблокирован из-за подозрения в мошенничестве',
            'general_decline' => 'Причина не детализирована',
            'identification_required' => 'Превышены ограничения на платежи для кошелька ЮMoney',
            'insufficient_funds' => 'Не хватает денег для оплаты',
            'internal_timeout' => 'Технические неполадки на стороне ЮKassa',
            'invalid_card_number' => 'Неправильно указан номер карты',
            'invalid_csc' => 'Неправильно указан код CVV2 (CVC2, CID)',
            'issuer_unavailable' => 'Организация, выпустившая платежное средство, недоступна.',
            'payment_method_limit_exceeded' => 'Исчерпан лимит платежей для данного платежного средства или вашего магазина',
            'payment_method_restricted' => 'Запрещены операции данным платежным средством ',
            'permission_revoked' => 'Нельзя провести безакцептное списание: пользователь отозвал разрешение на автоплатежи',
            'unsupported_mobile_operator' => 'Нельзя заплатить с номера телефона этого мобильного оператора',
        ];
    }

    /**
     * список конечных статусов, после которых автосписание не возможны
     * @return array
     */
    public static function getFatal()
    {
        return [
            'call_issuer',
            'canceled_by_merchant',
            'deal_expired',
            'expired_on_capture',
            'general_decline',
            'identification_required',
            'payment_method_restricted',
            'permission_revoked',
        ];
    }

    /**
     * @return boolean
     */
    public function isFatal()
    {
        $status = self::getFatal();
        return isset($status[$this->data]);
    }

    /**
     * получить описание статуса
     * @param  string $name 
     * @return string
     */
    public static function getDescription($name)
    {
        if (!$name) return '';
        $object = new self($name);
        $object->build();
        return $object->getResult();
    }
}