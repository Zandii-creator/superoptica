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
        $name = fake()->unique()->randomElement([
            'Rectangular', 'Redonda', 'Cuadrada', 'Aviador', 'Gato',
        ]).' '.fake()->unique()->numerify('##');

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'sort' => 0,
            'is_active' => true,
        ];
    }
}
