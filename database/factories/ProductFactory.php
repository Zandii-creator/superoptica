<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\ProductType;
use App\Models\Brand;
use App\Models\Material;
use App\Models\Product;
use App\Models\Shape;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $model = fake()->unique()->numerify('#####');

        return [
            'brand_id' => Brand::factory(),
            'material_id' => Material::factory(),
            'shape_id' => Shape::factory(),
            'model' => $model,
            'slug' => 'modelo-'.Str::slug($model),
            'type' => ProductType::Monturas,
            'gender' => Gender::Unisex,
            'lens_width' => 52,
            'bridge_width' => 18,
            'temple_length' => 140,
            'price_from' => fake()->numberBetween(800, 9000),
            'short_description' => fake()->sentence(8),
            'description' => fake()->paragraph(),
            'is_featured' => false,
            'is_active' => true,
            'published_at' => now(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'is_active' => false,
            'published_at' => null,
        ]);
    }
}
