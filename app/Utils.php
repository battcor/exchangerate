<?php

namespace App;

class Utils
{
    /**
     * Checks if country code belongs to EU
     *
     * @param string $code Country code
     *
     * @return boolean
     */
    public static function isEu($code)
    {
        $countries = [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR',
            'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO',
            'SE', 'SI', 'SK'
        ];
        return in_array($code, $countries);
    }

    /**
     * Rounds up value
     *
     * @param float $value
     * @param integer $precision
     *
     * @return float
     */
    public static function ceil($value, $precision = 3)
    {
        $precision = str_pad('1', $precision, '0');
        return ceil($value * $precision) / $precision;
    }
}
