<?php

namespace App\Support;

use App\Models\Product;
use App\Models\Service;
use App\Models\SiteSettings;
use App\Models\Variant;

class WhatsApp
{
    /**
     * Enlace wa.me con el numero configurado y el mensaje ya codificado.
     */
    public static function link(string $message): string
    {
        return 'https://wa.me/'.static::number().'?text='.rawurlencode($message);
    }

    /**
     * Consulta desde la ficha de un producto, con modelo y color.
     */
    public static function productLink(Product $product, ?Variant $variant = null): string
    {
        $message = sprintf(
            'Hola, me interesa la montura %s modelo %s',
            $product->brand?->name ?? 'SuperÓptica 20 20',
            $product->model,
        );

        if ($variant !== null) {
            $message .= ' en color '.$variant->color_name;
        }

        return static::link($message.'.');
    }

    /**
     * Consulta sobre un servicio concreto.
     */
    public static function serviceLink(Service $service): string
    {
        return static::link('Hola, quiero información sobre el servicio de '.$service->name.'.');
    }

    /**
     * Numero en formato internacional, solo digitos.
     */
    public static function number(): string
    {
        return preg_replace('/\D/', '', (string) SiteSettings::current()->whatsapp_number) ?? '';
    }
}
