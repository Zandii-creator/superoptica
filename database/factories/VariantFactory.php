<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Variant>
 */
class VariantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'color_name' => fake()->randomElement(['Negro', 'Carey', 'Azul', 'Plateado', 'Transparente']),
            'color_hex' => fake()->hexColor(),
            'price' => null,
            'sort' => 0,
        ];
    }
}
