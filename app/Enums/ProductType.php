<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductType: string implements HasLabel
{
    case Monturas = 'monturas';
    case Espejuelos = 'espejuelos';
    case LentesDeContacto = 'lentes-de-contacto';
    case Liquidos = 'liquidos';

    public function getLabel(): string
    {
        return match ($this) {
            self::Monturas => 'Monturas',
            self::Espejuelos => 'Espejuelos',
            self::LentesDeContacto => 'Lentes de contacto',
            self::Liquidos => 'Líquidos',
        };
    }
}
