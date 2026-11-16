<?php

namespace Database\Seeders;

use App\Models\Material;
use App\Models\Service;
use App\Models\Shape;
use Illuminate\Database\Seeder;

class CatalogDataSeeder extends Seeder
{
    /**
     * Taxonomias y servicios con los que arranca el negocio.
     */
    public function run(): void
    {
        $materials = [
            ['name' => 'Acetato', 'slug' => 'acetato'],
            ['name' => 'Metal', 'slug' => 'metal'],
            ['name' => 'Titanio', 'slug' => 'titanio'],
            ['name' => 'TR90', 'slug' => 'tr90'],
            ['name' => 'Acero inoxidable', 'slug' => 'acero-inoxidable'],
        ];

        foreach ($materials as $sort => $material) {
            Material::query()->updateOrCreate(
                ['slug' => $material['slug']],
                $material + ['sort' => $sort, 'is_active' => true],
            );
        }

        $shapes = [
            ['name' => 'Rectangular', 'slug' => 'rectangular'],
            ['name' => 'Redonda', 'slug' => 'redonda'],
            ['name' => 'Cuadrada', 'slug' => 'cuadrada'],
            ['name' => 'Aviador', 'slug' => 'aviador'],
            ['name' => 'Gato', 'slug' => 'gato'],
        ];

        foreach ($shapes as $sort => $shape) {
            Shape::query()->updateOrCreate(
                ['slug' => $shape['slug']],
                $shape + ['sort' => $sort, 'is_active' => true],
            );
        }

        $services = [
            [
                'name' => 'Examen visual',
                'slug' => 'examen-visual',
                'description' => 'Revisión de la vista con equipos del local para saber qué graduación necesitas.',
                'icon' => 'eye',
            ],
            [
                'name' => 'Ajuste de espejuelos',
                'slug' => 'ajuste-de-espejuelos',
                'description' => 'Ajustamos monturas y varillas para que los espejuelos queden cómodos y firmes.',
                'icon' => 'wrench',
            ],
            [
                'name' => 'Graduación',
                'slug' => 'graduacion',
                'description' => 'Montamos los lentes con la receta de tu especialista y verificamos el resultado.',
                'icon' => 'glasses',
            ],
        ];

        foreach ($services as $sort => $service) {
            Service::query()->updateOrCreate(
                ['slug' => $service['slug']],
                $service + ['sort' => $sort + 1, 'is_active' => true],
            );
        }
    }
}
