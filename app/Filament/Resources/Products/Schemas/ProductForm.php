<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Enums\Gender;
use App\Enums\ProductType;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del producto')
                    ->columns(2)
                    ->schema([
                        Select::make('brand_id')
                            ->label('Marca')
                            ->relationship('brand', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('model')
                            ->label('Modelo')
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
                            ->unique(ignoreRecord: true)
                            ->helperText('Se genera a partir del modelo; puede ajustarse.'),
                        Select::make('type')
                            ->label('Tipo')
                            ->options(ProductType::class)
                            ->required(),
                        Select::make('gender')
                            ->label('Género')
                            ->options(Gender::class)
                            ->required(),
                        TextInput::make('price_from')
                            ->label('Precio desde (CUP)')
                            ->required()
                            ->integer()
                            ->minValue(0)
                            ->suffix('CUP'),
                    ]),

                Section::make('Características')
                    ->columns(3)
                    ->schema([
                        Select::make('material_id')
                            ->label('Material')
                            ->relationship('material', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('shape_id')
                            ->label('Forma')
                            ->relationship('shape', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('lens_width')
                            ->label('Lente (mm)')
                            ->integer()
                            ->minValue(0)
                            ->maxValue(100),
                        TextInput::make('bridge_width')
                            ->label('Puente (mm)')
                            ->integer()
                            ->minValue(0)
                            ->maxValue(60),
                        TextInput::make('temple_length')
                            ->label('Varilla (mm)')
                            ->integer()
                            ->minValue(0)
                            ->maxValue(200),
                    ]),

                Section::make('Descripción')
                    ->schema([
                        TextInput::make('short_description')
                            ->label('Descripción corta')
                            ->maxLength(300)
                            ->helperText('Aparece en la tarjeta del catálogo.'),
                        Textarea::make('description')
                            ->label('Descripción completa')
                            ->rows(5),
                    ]),

                Section::make('Imágenes del producto')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('images')
                            ->label('Galería')
                            ->collection('images')
                            ->disk('public')
                            ->multiple()
                            ->image()
                            ->maxFiles(8)
                            ->helperText('La primera imagen se usa como portada.'),
                    ]),

                Section::make('Variantes de color')
                    ->schema([
                        Repeater::make('variants')
                            ->relationship()
                            ->label('Colores disponibles')
                            ->columns(2)
                            ->collapsible()
                            ->defaultItems(1)
                            ->itemLabel(fn (array $state): ?string => $state['color_name'] ?? null)
                            ->schema([
                                TextInput::make('color_name')
                                    ->label('Color')
                                    ->required()
                                    ->maxLength(100),
                                ColorPicker::make('color_hex')
                                    ->label('Muestra')
                                    ->helperText('Opcional, para mostrar el círculo de color.'),
                                TextInput::make('price')
                                    ->label('Precio de esta variante (CUP)')
                                    ->integer()
                                    ->minValue(0)
                                    ->helperText('Si se deja vacío se usa el precio del producto.'),
                                TextInput::make('sort')
                                    ->label('Orden')
                                    ->integer()
                                    ->default(0),
                                SpatieMediaLibraryFileUpload::make('images')
                                    ->label('Fotos de este color')
                                    ->collection('images')
                                    ->disk('public')
                                    ->multiple()
                                    ->image()
                                    ->maxFiles(4)
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Section::make('Publicación')
                    ->columns(3)
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                        Toggle::make('is_featured')
                            ->label('Destacado en la home')
                            ->default(false),
                        DateTimePicker::make('published_at')
                            ->label('Publicado el')
                            ->helperText('Mientras esté vacío, el producto no aparece en el sitio.'),
                    ]),
            ]);
    }
}
