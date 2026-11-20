<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
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

    public function test_the_seeded_credentials_can_log_in_through_the_panel_form(): void
    {
        config([
            'admin.name' => 'Administrador',
            'admin.email' => 'santiestebantoca@gmail.com',
            'admin.password' => 'santiestebantoca.admin',
        ]);

        $this->seed(AdminUserSeeder::class);

        Livewire::test(Login::class)
            ->fillForm([
                'email' => 'santiestebantoca@gmail.com',
                'password' => 'santiestebantoca.admin',
            ])
            ->call('authenticate')
            ->assertHasNoFormErrors();

        $this->assertAuthenticated();
    }

    public function test_the_login_page_serves_livewire_from_the_published_assets(): void
    {
        $this->assertFileExists(public_path('vendor/livewire/manifest.json'));

        $this->get('/admin/login')
            ->assertOk()
            ->assertSee('vendor/livewire/livewire', false);
    }

    public function test_the_livewire_asset_url_keeps_the_published_subdirectory(): void
    {
        $this->withServerVariables([
            'SCRIPT_NAME' => '/superoptica/index.php',
            'SCRIPT_FILENAME' => public_path('index.php'),
            'PHP_SELF' => '/superoptica/index.php',
        ])->get('/superoptica/admin/login')
            ->assertOk()
            ->assertSee('/superoptica/vendor/livewire/livewire', false);
    }

    public function test_the_livewire_update_url_keeps_the_published_subdirectory(): void
    {
        $this->withServerVariables([
            'SCRIPT_NAME' => '/superoptica/index.php',
            'SCRIPT_FILENAME' => public_path('index.php'),
            'PHP_SELF' => '/superoptica/index.php',
        ])->get('/superoptica/admin/login')
            ->assertOk()
            ->assertSee('/superoptica/livewire/update', false);
    }

    public function test_the_admin_seeder_is_idempotent(): void
    {
        $this->seed(AdminUserSeeder::class);
        $this->seed(AdminUserSeeder::class);

        $this->assertSame(1, User::query()->count());
    }
}
