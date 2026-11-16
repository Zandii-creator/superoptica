<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSettings extends Model
{
    public const CACHE_KEY = 'site_settings.current';

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::saved(static fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(static fn () => Cache::forget(self::CACHE_KEY));
    }

    /**
     * Unica fila de ajustes del sitio; se crea con los valores por defecto
     * y se sirve desde cache hasta que se guarda un cambio.
     */
    public static function current(): self
    {
        return Cache::rememberForever(
            self::CACHE_KEY,
            static fn (): self => static::query()->firstOrCreate([], static::defaults()),
        );
    }

    /**
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'site_name' => 'SuperÓptica 20 20',
            'currency_label' => 'CUP',
            'currency_notice' => 'Precios en moneda nacional (CUP).',
        ];
    }

    /**
     * Horario de la semana listo para la vista; null significa cerrado.
     *
     * @return list<array{day: string, hours: string|null}>
     */
    public function weeklyHours(): array
    {
        return [
            ['day' => 'Lunes', 'hours' => $this->hours_monday],
            ['day' => 'Martes', 'hours' => $this->hours_tuesday],
            ['day' => 'Miércoles', 'hours' => $this->hours_wednesday],
            ['day' => 'Jueves', 'hours' => $this->hours_thursday],
            ['day' => 'Viernes', 'hours' => $this->hours_friday],
            ['day' => 'Sábado', 'hours' => $this->hours_saturday],
            ['day' => 'Domingo', 'hours' => $this->hours_sunday],
        ];
    }

    public function hasPrivacyNotice(): bool
    {
        return filled($this->privacy_notice);
    }

    /**
     * Redes sociales configuradas; las vacias no generan enlace.
     *
     * @return list<array{label: string, url: string}>
     */
    public function socialLinks(): array
    {
        $links = [
            ['label' => 'Instagram', 'url' => $this->instagram_url],
            ['label' => 'Facebook', 'url' => $this->facebook_url],
        ];

        return array_values(array_filter(
            $links,
            static fn (array $link): bool => filled($link['url']),
        ));
    }

    public function telegramUrl(): ?string
    {
        $username = ltrim((string) $this->telegram_username, '@');

        return filled($username) ? 'https://t.me/'.$username : null;
    }

    public function phoneUrl(): ?string
    {
        $number = preg_replace('/[^0-9+]/', '', (string) $this->phone_number);

        return filled($number) ? 'tel:'.$number : null;
    }

    public function fullAddress(): string
    {
        $parts = array_filter([
            $this->address_street,
            filled($this->address_between) ? 'entre '.$this->address_between : null,
            $this->city,
            $this->province,
            $this->country,
        ]);

        return implode(', ', $parts);
    }

    public function heroTitle(): string
    {
        return $this->hero_title ?: 'Espejuelos para verte mejor, en Ciego de Ávila';
    }

    public function heroSubtitle(): string
    {
        return $this->hero_subtitle ?: 'Monturas, lentes de contacto y líquidos con la atención de siempre.';
    }
}
