<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    /**
     * Crea el unico usuario del panel, de forma idempotente.
     */
    public function run(): void
    {
        if (app()->environment('production') && ! env('ADMIN_PASSWORD')) {
            throw new RuntimeException(
                'Define ADMIN_PASSWORD antes de sembrar el administrador en produccion.'
            );
        }

        User::query()->updateOrCreate(
            ['email' => config('admin.email')],
            [
                'name' => config('admin.name'),
                'password' => config('admin.password'),
            ],
        );
    }
}
