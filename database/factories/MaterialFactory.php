<?php

namespace Database\Factories;

use App\Models\Material;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Material>
 */
class MaterialFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Acetato', 'Metal', 'Titanio', 'TR90', 'Acero inoxidable',
        ]).' '.fake()->unique()->numerify('##');

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'sort' => 0,
            'is_active' => true,
        ];
    }
}
