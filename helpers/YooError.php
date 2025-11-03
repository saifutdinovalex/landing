<?php

declare(strict_types=1);

namespace helpers;

use ABaseObject;
use Yii;

class YooError extends ABaseObject
{
    protected string $data;
    
    public function __construct(string $name = '')
    {
        $this->data = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function build(): self
    {
        $texts = $this->getText();
        $this->result = $this->data 
            ? ($texts[$this->data] ?? 'Неизвестная ошибка')
            : $texts;

        return $this;
    }

    /**
     * Get YooMoney status descriptions
     * 
     * Returns an array mapping YooMoney error codes to human-readable
     * descriptions in Russian language.
     * 
     * @return array<string, string> Error codes and their descriptions
     */
    protected function getText(): array
    {
        return [
            '3d_secure_failed' => 'Не пройдена аутентификация по 3-D Secure',
            'call_issuer' => 'Оплата данным платежным средством отклонена по неизвестным причинам',
            'canceled_by_merchant' => 'Платеж возвращен при холдировании',
            'card_expired' => 'Истек срок действия банковской карты',
            'country_forbidden' => 'Нельзя заплатить банковской картой, выпущенной в этой стране',
            'deal_expired' => 'Закончился срок жизни сделки',
            'expired_on_capture' => 'Истек срок списания оплаты',
            'expired_on_confirmation' => 'Истек срок оплаты',
            'fraud_suspected' => 'Платеж заблокирован из-за подозрения в мошенничестве',
            'general_decline' => 'Причина не детализирована',
            'identification_required' => 'Превышены ограничения на платежи для кошелька ЮMoney',
            'insufficient_funds' => 'Не хватает денег для оплаты',
            'internal_timeout' => 'Технические неполадки на стороне ЮKassa',
            'invalid_card_number' => 'Неправильно указан номер карты',
            'invalid_csc' => 'Неправильно указан код CVV2 (CVC2, CID)',
            'issuer_unavailable' => 'Организация, выпустившая платежное средство, недоступна',
            'payment_method_limit_exceeded' => 'Исчерпан лимит платежей для данного платежного средства или вашего магазина',
            'payment_method_restricted' => 'Запрещены операции данным платежным средством',
            'permission_revoked' => 'Нельзя провести безакцептное списание: пользователь отозвал разрешение на автоплатежи',
            'unsupported_mobile_operator' => 'Нельзя заплатить с номера телефона этого мобильного оператора',
        ];
    }

    /**
     * Get list of fatal statuses
     * 
     * Returns error codes that represent terminal states where
     * automatic payment collection is no longer possible.
     * 
     * @return array<string> Array of fatal error codes
     */
    public static function getFatal(): array
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
     * Check if current error is fatal
     * 
     * Determines whether the current error code represents a fatal
     * state where automatic payment retry is not possible.
     * 
     * @return bool True if error is fatal, false otherwise
     */
    public function isFatal(): bool
    {
        $fatalStatuses = self::getFatal();
        
        return in_array($this->data, $fatalStatuses, true);
    }

    /**
     * Get error description by code
     * 
     * Static helper method to retrieve human-readable description
     * for a given YooMoney error code.
     * 
     * @param string $name Error code
     * @return string Error description or empty string if not found
     */
    public static function getDescription(string $name): string
    {
        if (!$name) {
            return '';
        }
        
        $object = new self($name);
        $object->build();
        
        return (string) $object->getResult();
    }
}