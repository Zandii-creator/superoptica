<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();

            // Identidad
            $table->string('site_name')->default('SuperÓptica 20 20');
            $table->string('tagline')->nullable();
            $table->string('favicon')->nullable();

            // Ubicacion de la tienda fisica
            $table->string('address_street')->nullable();
            $table->string('address_between')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('country')->nullable();

            // Horarios: null significa cerrado
            $table->string('hours_monday')->nullable();
            $table->string('hours_tuesday')->nullable();
            $table->string('hours_wednesday')->nullable();
            $table->string('hours_thursday')->nullable();
            $table->string('hours_friday')->nullable();
            $table->string('hours_saturday')->nullable();
            $table->string('hours_sunday')->nullable();

            // Canales de contacto
            $table->string('whatsapp_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('telegram_username')->nullable();

            // Redes sociales y mapa
            $table->string('instagram_url')->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('map_url')->nullable();
            $table->string('map_image')->nullable();

            // Moneda y avisos legales
            $table->string('currency_label')->default('CUP');
            $table->string('currency_notice')->default('Precios en moneda nacional (CUP).');
            $table->text('privacy_notice')->nullable();

            // Textos de la pagina de inicio y SEO
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description', 300)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
