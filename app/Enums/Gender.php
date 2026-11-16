<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Gender: string implements HasLabel
{
    case Unisex = 'unisex';
    case Femenino = 'femenino';
    case Masculino = 'masculino';
    case Infantil = 'infantil';

    public function getLabel(): string
    {
        return match ($this) {
            self::Unisex => 'Unisex',
            self::Femenino => 'Femenino',
            self::Masculino => 'Masculino',
            self::Infantil => 'Infantil',
        };
    }
}
