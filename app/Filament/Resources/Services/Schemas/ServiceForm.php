<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state): void {
                        $set('slug', Str::slug((string) $state));
                    }),
                TextInput::make('slug')
                    ->label('URL')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Textarea::make('description')
                    ->label('Descripción')
                    ->required()
                    ->rows(4)
                    ->columnSpanFull()
                    ->helperText('Qué resuelve el servicio y a quién está dirigido, sin prometer resultados clínicos.'),
                TextInput::make('icon')
                    ->label('Icono')
                    ->maxLength(100)
                    ->helperText('Nombre del icono; opcional.'),
                TextInput::make('sort')
                    ->label('Orden')
                    ->required()
                    ->integer()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('Activo')
                    ->default(true),
            ]);
    }
}
