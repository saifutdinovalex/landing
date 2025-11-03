<?php

declare(strict_types=1);

namespace landing;

/**
 * Base configuration class for landing pages
 * 
 * Provides static methods for retrieving pricing information
 * and other configuration data for landing page tariffs
 */
class BaseConfig
{
    /**
     * Get price for specified tariff
     * 
     * Retrieves the cost associated with a given tariff name.
     * This is a base implementation that returns 0 - child classes
     * should override this method to provide actual pricing logic.
     * 
     * @param string $name Tariff name identifier
     * @return float Tariff price amount
     */
    public static function getPrice(string $name): float
    {
        // Base implementation - should be overridden in child classes
        // to provide actual pricing data retrieval logic
        return 0.0;
    }
}