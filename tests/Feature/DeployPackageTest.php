<?php

namespace Tests\Feature;

use Tests\TestCase;

class DeployPackageTest extends TestCase
{
    public function test_the_packaging_script_prepares_both_zips_without_touching_cpanel(): void
    {
        $script = strtolower(file_get_contents(base_path('scripts/build-deploy.ps1')));

        foreach ([
            'artisan test',
            'pint --test',
            'npm run build',
            'install --no-dev',
            'key:generate --show',
            '*.sqlite',
            'superoptica-public-',
            'public_html/superoptica',
            'zipfile',
        ] as $needle) {
            $this->assertStringContainsString($needle, $script, "El script no incluye: {$needle}");
        }

        foreach (['uapi', 'cpanel api', 'curl '] as $forbidden) {
            $this->assertStringNotContainsString($forbidden, $script, "El script no debe contener: {$forbidden}");
        }
    }

    public function test_the_public_index_template_points_to_the_app_outside_the_web_root(): void
    {
        $template = file_get_contents(base_path('deploy/public-index.php'));

        $this->assertStringContainsString(
            "__DIR__.'/../../superoptica-app/storage/framework/maintenance.php'",
            $template,
        );
        $this->assertStringContainsString(
            "__DIR__.'/../../superoptica-app/vendor/autoload.php'",
            $template,
        );
        $this->assertStringContainsString(
            "__DIR__.'/../../superoptica-app/bootstrap/app.php'",
            $template,
        );
    }
}
