<?php

namespace Database\Factories;

use App\Models\Shape;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Shape>
 */
class ShapeFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement([
            'Rectangular', 'Redonda', 'Cuadrada', 'Aviador', 'Gato',
        ]).' '.fake()->unique()->numberBetween(1, 999999);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'sort' => 0,
            'is_active' => true,
        ];
    }
}
