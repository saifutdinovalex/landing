<?php

namespace helpers;

use Yii;

class Hash
{
    /**
     * получить хэшированный номер счета
     * @return string 
     */
    public static function get($invoice_id, $token)
    {
        return $invoice_id.'__'.hash_hmac('sha256', $token, \Yii::getAlias('@secret'));
    }
}