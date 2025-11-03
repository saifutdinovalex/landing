<?php

declare(strict_types=1);

namespace helpers;

use Yii;

class Hash
{
    /**
     * Get hashed invoice number
     * 
     * Generates a secure hash for invoice identification by combining
     * the invoice ID with a token using HMAC-SHA256 algorithm.
     * This provides both identification and verification capabilities.
     * 
     * @param int|string $invoice_id The invoice identifier
     * @param string $token The security token for hashing
     * @return string Hashed invoice identifier in format "id__hash"
     */
    public static function get($invoice_id, string $token): string
    {
        return $invoice_id . '__' . hash_hmac(
            'sha256', 
            $token, 
            Yii::getAlias('@secret')
        );
    }
}