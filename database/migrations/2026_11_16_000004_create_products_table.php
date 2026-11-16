<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->restrictOnDelete();
            $table->foreignId('material_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('shape_id')->nullable()->constrained()->nullOnDelete();
            $table->string('model');
            $table->string('slug')->unique();
            $table->string('type');
            $table->string('gender');
            $table->unsignedSmallInteger('lens_width')->nullable();
            $table->unsignedSmallInteger('bridge_width')->nullable();
            $table->unsignedSmallInteger('temple_length')->nullable();
            $table->unsignedInteger('price_from');
            $table->string('short_description', 300)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('model');
            $table->index('type');
            $table->index('gender');
            $table->index('brand_id');
            $table->index('material_id');
            $table->index('shape_id');
            $table->index('is_featured');
            $table->index(['is_active', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
