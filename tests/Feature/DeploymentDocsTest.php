<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class DeploymentDocsTest extends TestCase
{
    public function test_the_environment_example_is_ready_for_production_and_only_uses_espejuelos(): void
    {
        $env = file_get_contents(base_path('.env.example'));

        foreach ([
            'APP_ENV=production',
            'APP_DEBUG=false',
            'DB_CONNECTION=mysql',
            'DB_DATABASE=espejuelos',
            'QUEUE_CONNECTION=sync',
            'MEDIA_DISK=public',
        ] as $line) {
            $this->assertStringContainsString($line, $env);
        }

        preg_match_all('/^DB_DATABASE=(.+)$/m', $env, $matches);

        $this->assertSame(['espejuelos'], array_map('trim', $matches[1]));
    }

    public function test_the_deployment_guide_documents_the_manual_steps_and_the_rollback(): void
    {
        $guide = file_get_contents(base_path('docs/despliegue.md'));

        foreach ([
            'composer install --no-dev',
            'php artisan migrate --force',
            'php artisan storage:link',
            'php artisan optimize',
            'public/build',
            'public/fonts',
            'Revertir',
            'espejuelos',
        ] as $needle) {
            $this->assertStringContainsString($needle, $guide);
        }
    }

    public function test_the_cron_instruction_contains_the_command_the_path_and_the_frequency(): void
    {
        $guide = file_get_contents(base_path('docs/despliegue.md'));

        $this->assertStringContainsString('schedule:run', $guide);
        $this->assertStringContainsString('/home/', $guide);
        $this->assertMatchesRegularExpression('/cada minuto|\* \* \* \* \*/', $guide);
    }

    public function test_the_verification_report_records_the_suite_and_the_openspec_validation(): void
    {
        $report = file_get_contents(base_path('docs/verificacion.md'));

        $this->assertStringContainsString('artisan test', $report);
        $this->assertStringContainsString('openspec', strtolower($report));
        $this->assertStringContainsString('Lighthouse', $report);
        $this->assertStringContainsString('500 KB', $report);
    }

    public function test_the_project_does_not_automate_cpanel_actions(): void
    {
        $directories = ['app', 'bootstrap', 'config', 'database', 'routes'];

        $files = collect($directories)
            ->flatMap(fn (string $directory) => File::allFiles(base_path($directory)))
            ->filter(fn ($file) => in_array($file->getExtension(), ['php', 'sh', 'ps1', 'bat', 'yml', 'yaml'], true));

        foreach ($files as $file) {
            $contents = strtolower(file_get_contents($file->getPathname()));

            $this->assertStringNotContainsString('uapi', $contents, $file->getPathname());
            $this->assertStringNotContainsString('cpanel api', $contents, $file->getPathname());
        }
    }
}
