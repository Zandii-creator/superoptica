<?php

namespace App\Support;

use App\Models\SiteSettings;

class Price
{
    /**
     * Formatea un monto en CUP: coma de miles, sin decimales.
     */
    public static function format(int $amount): string
    {
        return number_format($amount, 0, '.', ',').' '.static::currencyLabel();
    }

    /**
     * Precio referencial de un producto.
     */
    public static function from(int $amount): string
    {
        return 'Desde '.static::format($amount);
    }

    public static function currencyLabel(): string
    {
        return SiteSettings::current()->currency_label ?: 'CUP';
    }
}
