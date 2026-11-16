<?php

namespace App\Filament\Pages;

use App\Models\SiteSettings;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ManageSiteSettings extends Page
{
    protected string $view = 'filament.pages.manage-site-settings';

    protected static string|\UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Ajustes del sitio';

    protected static ?string $title = 'Ajustes del sitio';

    protected static ?string $slug = 'ajustes';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(SiteSettings::current()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identidad')
                    ->columns(2)
                    ->schema([
                        TextInput::make('site_name')
                            ->label('Nombre del negocio')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('tagline')
                            ->label('Eslogan')
                            ->maxLength(255),
                        FileUpload::make('favicon')
                            ->label('Favicon')
                            ->image()
                            ->disk('public')
                            ->directory('site')
                            ->columnSpanFull(),
                    ]),

                Section::make('Ubicación de la tienda')
                    ->columns(2)
                    ->schema([
                        TextInput::make('address_street')
                            ->label('Calle y número')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('address_between')
                            ->label('Entre calles')
                            ->maxLength(255),
                        TextInput::make('city')
                            ->label('Ciudad')
                            ->maxLength(255),
                        TextInput::make('province')
                            ->label('Provincia')
                            ->maxLength(255),
                        TextInput::make('country')
                            ->label('País')
                            ->maxLength(255),
                    ]),

                Section::make('Horarios')
                    ->description('Deja vacío el día que permanezca cerrado.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('hours_monday')->label('Lunes')->maxLength(100)->placeholder('Cerrado'),
                        TextInput::make('hours_tuesday')->label('Martes')->maxLength(100)->placeholder('Cerrado'),
                        TextInput::make('hours_wednesday')->label('Miércoles')->maxLength(100)->placeholder('Cerrado'),
                        TextInput::make('hours_thursday')->label('Jueves')->maxLength(100)->placeholder('Cerrado'),
                        TextInput::make('hours_friday')->label('Viernes')->maxLength(100)->placeholder('Cerrado'),
                        TextInput::make('hours_saturday')->label('Sábado')->maxLength(100)->placeholder('Cerrado'),
                        TextInput::make('hours_sunday')->label('Domingo')->maxLength(100)->placeholder('Cerrado'),
                    ]),

                Section::make('Contacto')
                    ->columns(2)
                    ->schema([
                        TextInput::make('whatsapp_number')
                            ->label('WhatsApp')
                            ->required()
                            ->rule('regex:/^\+?[0-9]{8,15}$/')
                            ->helperText('Solo dígitos, con código de país. Ejemplo: 5355123456'),
                        TextInput::make('phone_number')
                            ->label('Teléfono fijo')
                            ->rule('regex:/^\+?[0-9]{8,15}$/'),
                        TextInput::make('telegram_username')
                            ->label('Usuario de Telegram')
                            ->maxLength(100)
                            ->helperText('Sin @.'),
                    ]),

                Section::make('Redes sociales y mapa')
                    ->columns(2)
                    ->schema([
                        TextInput::make('instagram_url')
                            ->label('Instagram')
                            ->url(),
                        TextInput::make('facebook_url')
                            ->label('Facebook')
                            ->url(),
                        TextInput::make('map_url')
                            ->label('Enlace del mapa')
                            ->url(),
                        FileUpload::make('map_image')
                            ->label('Imagen de referencia del mapa')
                            ->image()
                            ->disk('public')
                            ->directory('site'),
                    ]),

                Section::make('Moneda y avisos')
                    ->columns(2)
                    ->schema([
                        TextInput::make('currency_label')
                            ->label('Etiqueta de moneda')
                            ->required()
                            ->maxLength(10)
                            ->default('CUP'),
                        TextInput::make('currency_notice')
                            ->label('Aviso de precios')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('privacy_notice')
                            ->label('Aviso de privacidad')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Section::make('Portada y SEO')
                    ->columns(2)
                    ->schema([
                        TextInput::make('hero_title')
                            ->label('Título de portada')
                            ->maxLength(255),
                        TextInput::make('hero_subtitle')
                            ->label('Subtítulo de portada')
                            ->maxLength(255),
                        TextInput::make('seo_title')
                            ->label('Título para buscadores')
                            ->maxLength(255),
                        TextInput::make('seo_description')
                            ->label('Descripción para buscadores')
                            ->maxLength(300),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        SiteSettings::current()->update($this->form->getState());

        Notification::make()
            ->success()
            ->title('Ajustes guardados')
            ->send();
    }
}
