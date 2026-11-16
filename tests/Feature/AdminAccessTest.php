<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_an_authenticated_user_can_open_the_admin_panel(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admin')->assertOk();
    }

    public function test_the_admin_seeder_creates_one_hashed_administrator(): void
    {
        $this->seed(AdminUserSeeder::class);

        $admin = User::query()->sole();

        $this->assertSame(config('admin.email'), $admin->email);
        $this->assertTrue(Hash::check(config('admin.password'), $admin->password));
    }

    public function test_the_admin_seeder_is_idempotent(): void
    {
        $this->seed(AdminUserSeeder::class);
        $this->seed(AdminUserSeeder::class);

        $this->assertSame(1, User::query()->count());
    }
}
