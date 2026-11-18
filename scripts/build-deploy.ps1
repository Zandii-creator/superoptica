#Requires -Version 5.1
<#
.SYNOPSIS
    Prepara los paquetes de despliegue manual para cPanel.

.DESCRIPTION
    Ejecuta la preparacion local del proyecto y genera dos archivos zip listos para
    subir por el Administrador de archivos de cPanel:

      superoptica-app-<fecha>.zip      -> se extrae en /home/USUARIO/superoptica-app
      superoptica-public-<fecha>.zip   -> se extrae en /home/USUARIO/public_html/superoptica

    El script no sube nada, no llama a la API de cPanel y no toca la base de datos.
    Los comandos de artisan en el servidor se lanzan a mano desde los Trabajos de
    cron, como explica docs/despliegue.md.

.PARAMETER PhpPath
    Ruta de php.exe. Por defecto la de este equipo.

.PARAMETER ComposerPath
    Ruta de composer.phar. Por defecto la de este equipo.

.PARAMETER OutputDirectory
    Carpeta donde se dejan los zip y el resumen. Por defecto storage/app/deploy.

.PARAMETER SkipTests
    Omite la suite de pruebas y la comprobacion de formato.

.PARAMETER SkipAssets
    Omite npm run build y reutiliza el public/build existente.

.PARAMETER KeepStage
    Conserva las carpetas temporales de empaquetado para revisarlas.

.EXAMPLE
    .\scripts\build-deploy.ps1

.EXAMPLE
    .\scripts\build-deploy.ps1 -SkipTests
#>
[CmdletBinding()]
param(
    [string] $PhpPath = "$env:LOCALAPPDATA\php\8.3\php.exe",
    [string] $ComposerPath = "$env:LOCALAPPDATA\composer\composer.phar",
    [string] $OutputDirectory,
    [switch] $SkipTests,
    [switch] $SkipAssets,
    [switch] $KeepStage
)

$ErrorActionPreference = 'Stop'
Set-StrictMode -Version Latest

$projectRoot = Split-Path -Parent $PSScriptRoot
$php = $PhpPath
$artisan = Join-Path $projectRoot 'artisan'
$indexTemplate = Join-Path $projectRoot 'deploy\public-index.php'

function Write-Step {
    param([string] $Message)

    Write-Host ''
    Write-Host "==> $Message" -ForegroundColor Cyan
}

function Assert-LastExitCode {
    param([string] $Message)

    if ($LASTEXITCODE -ne 0) {
        throw "$Message (codigo $LASTEXITCODE)"
    }
}

function Remove-TreeContent {
    param([string] $Path)

    if (-not (Test-Path -LiteralPath $Path)) {
        return
    }

    Get-ChildItem -LiteralPath $Path -Force |
        Where-Object { $_.Name -ne '.gitignore' } |
        Remove-Item -Recurse -Force -ErrorAction SilentlyContinue
}

function New-ZipFromDirectory {
    param(
        [string] $SourceDirectory,
        [string] $DestinationArchive
    )

    # ZipFile.CreateFromDirectory usa "\" en Windows y Linux no lo interpreta como
    # separador al extraer. Las entradas se crean a mano con "/".
    $source = (Get-Item -LiteralPath $SourceDirectory).FullName.TrimEnd('\')
    $archive = [System.IO.Compression.ZipFile]::Open(
        $DestinationArchive,
        [System.IO.Compression.ZipArchiveMode]::Create
    )

    try {
        Get-ChildItem -LiteralPath $source -Recurse -File -Force | ForEach-Object {
            $relative = $_.FullName.Substring($source.Length + 1).Replace('\', '/')
            [System.IO.Compression.ZipFileExtensions]::CreateEntryFromFile(
                $archive,
                $_.FullName,
                $relative,
                [System.IO.Compression.CompressionLevel]::Optimal
            ) | Out-Null
        }
    } finally {
        $archive.Dispose()
    }
}

function Invoke-Robocopy {
    param([string[]] $Arguments)

    & robocopy @Arguments
    if ($LASTEXITCODE -ge 8) {
        throw "robocopy fallo con codigo $LASTEXITCODE"
    }
}

$exitCode = 0
$stage = $null
$publicStage = $null

try {
    if (-not (Test-Path -LiteralPath $PhpPath)) {
        throw "No se encontro PHP en '$PhpPath'. Usa -PhpPath con la ruta de php.exe."
    }

    if (-not (Test-Path -LiteralPath $ComposerPath)) {
        throw "No se encontro composer.phar en '$ComposerPath'. Usa -ComposerPath."
    }

    if (-not (Test-Path -LiteralPath $indexTemplate)) {
        throw "Falta la plantilla deploy/public-index.php."
    }

    if (-not $SkipAssets -and -not (Get-Command npm -ErrorAction SilentlyContinue)) {
        throw 'No se encontro npm. Instala Node o usa -SkipAssets.'
    }

    if (-not $OutputDirectory) {
        $OutputDirectory = Join-Path $projectRoot 'storage\app\deploy'
    }

    New-Item -ItemType Directory -Force -Path $OutputDirectory | Out-Null
    $OutputDirectory = (Resolve-Path -LiteralPath $OutputDirectory).Path

    $stamp = Get-Date -Format 'yyyyMMdd-HHmm'
    $stage = Join-Path $env:TEMP "superoptica-deploy-$stamp"
    $publicStage = Join-Path $env:TEMP "superoptica-public-$stamp"
    $appZip = Join-Path $OutputDirectory "superoptica-app-$stamp.zip"
    $publicZip = Join-Path $OutputDirectory "superoptica-public-$stamp.zip"
    $summaryPath = Join-Path $OutputDirectory "despliegue-$stamp.txt"

    $env:COMPOSER_MAX_PARALLEL_HTTP = '1'

    Add-Type -AssemblyName System.IO.Compression
    Add-Type -AssemblyName System.IO.Compression.FileSystem

    Write-Step 'Instalando dependencias de desarrollo'
    Push-Location $projectRoot
    try {
        & $php $ComposerPath install --no-interaction
        Assert-LastExitCode 'Fallo composer install'
    } finally {
        Pop-Location
    }

    if (-not $SkipTests) {
        Write-Step 'Ejecutando la suite de pruebas'
        & $php $artisan test
        Assert-LastExitCode 'La suite de pruebas fallo'

        Write-Step 'Comprobando el formato'
        & $php vendor\bin\pint --test
        Assert-LastExitCode 'Pint encontro diferencias de formato'
    }

    if (-not $SkipAssets) {
        if (-not (Test-Path -LiteralPath (Join-Path $projectRoot 'node_modules'))) {
            Write-Step 'Instalando dependencias de Node'
            Push-Location $projectRoot
            try {
                & npm ci
                Assert-LastExitCode 'Fallo npm ci'
            } finally {
                Pop-Location
            }
        }

        Write-Step 'Compilando assets'
        Push-Location $projectRoot
        try {
            & npm run build
            Assert-LastExitCode 'Fallo npm run build'
        } finally {
            Pop-Location
        }
    }

    Write-Step 'Limpiando caches y publicando assets de Filament'
    Push-Location $projectRoot
    try {
        & $php $artisan optimize:clear
        Assert-LastExitCode 'Fallo optimize:clear'

        & $php $artisan filament:upgrade
        Assert-LastExitCode 'Fallo filament:upgrade'
    } finally {
        Pop-Location
    }

    Write-Step 'Generando la APP_KEY de produccion'
    $appKey = (& $php $artisan key:generate --show --no-ansi | Select-Object -Last 1)
    if (-not $appKey) {
        throw 'No se pudo generar la APP_KEY.'
    }

    $appKey = $appKey.Trim()
    if ($appKey -notmatch '^base64:') {
        throw "Salida inesperada de key:generate: $appKey"
    }

    Write-Step 'Copiando el proyecto a la carpeta temporal'
    Remove-Item -LiteralPath $stage -Recurse -Force -ErrorAction SilentlyContinue
    Invoke-Robocopy @(
        $projectRoot,
        $stage,
        '/E',
        '/XD', "$projectRoot\.git", "$projectRoot\node_modules", "$projectRoot\public\storage",
        "$projectRoot\storage\app\deploy", "$projectRoot\tests", "$projectRoot\openspec",
        "$projectRoot\.agents", "$projectRoot\deploy", "$projectRoot\scripts",
        '/XF', '.env', '*.sqlite', 'hot', '.phpunit.result.cache',
        '/NFL', '/NDL', '/NJH', '/NJS', '/NP'
    )

    Write-Step 'Quitando archivos que no van al servidor'
    Remove-Item -LiteralPath (Join-Path $stage '.env') -Force -ErrorAction SilentlyContinue
    Remove-Item -LiteralPath (Join-Path $stage 'public\hot') -Force -ErrorAction SilentlyContinue
    Get-ChildItem -LiteralPath (Join-Path $stage 'database') -Filter '*.sqlite*' -Force -ErrorAction SilentlyContinue |
        Remove-Item -Force -ErrorAction SilentlyContinue
    Get-ChildItem -LiteralPath (Join-Path $stage 'bootstrap\cache') -Filter '*.php' -Force -ErrorAction SilentlyContinue |
        Remove-Item -Force -ErrorAction SilentlyContinue

    Remove-TreeContent (Join-Path $stage 'storage\framework\cache\data')
    Remove-TreeContent (Join-Path $stage 'storage\framework\sessions')
    Remove-TreeContent (Join-Path $stage 'storage\framework\views')
    Remove-TreeContent (Join-Path $stage 'storage\framework\testing')
    Remove-TreeContent (Join-Path $stage 'storage\logs')
    Remove-TreeContent (Join-Path $stage 'storage\app\public')
    Remove-Item -LiteralPath (Join-Path $stage 'storage\app\deploy') -Recurse -Force -ErrorAction SilentlyContinue

    Write-Step 'Preparando las dependencias de produccion'
    Push-Location $stage
    try {
        & $php $ComposerPath install --no-dev --optimize-autoloader --no-interaction
        if ($LASTEXITCODE -ne 0) {
            Write-Warning 'No se pudieron quitar las dependencias de desarrollo; el zip lleva el vendor completo.'
        }
    } finally {
        Pop-Location
    }

    Add-Type -AssemblyName System.IO.Compression.FileSystem

    Write-Step 'Creando el zip de la aplicacion'
    Remove-Item -LiteralPath $appZip -Force -ErrorAction SilentlyContinue
    New-ZipFromDirectory -SourceDirectory $stage -DestinationArchive $appZip

    Write-Step 'Creando el zip de la carpeta publica'
    Remove-Item -LiteralPath $publicStage -Recurse -Force -ErrorAction SilentlyContinue
    Invoke-Robocopy @(
        (Join-Path $stage 'public'),
        $publicStage,
        '/E',
        '/NFL', '/NDL', '/NJH', '/NJS', '/NP'
    )

    Copy-Item -LiteralPath $indexTemplate -Destination (Join-Path $publicStage 'index.php') -Force
    Remove-Item -LiteralPath (Join-Path $publicStage 'hot') -Force -ErrorAction SilentlyContinue
    Remove-Item -LiteralPath (Join-Path $publicStage 'storage') -Recurse -Force -ErrorAction SilentlyContinue

    Remove-Item -LiteralPath $publicZip -Force -ErrorAction SilentlyContinue
    New-ZipFromDirectory -SourceDirectory $publicStage -DestinationArchive $publicZip

    Write-Step 'Escribiendo el resumen del despliegue'
    $summary = @(
        'Paquete de despliegue de SuperOptica 20 20',
        "Generado: $(Get-Date -Format 'yyyy-MM-dd HH:mm')",
        '',
        'Archivos:',
        "  Aplicacion: $appZip",
        "  Publica:    $publicZip",
        '',
        'APP_KEY para el .env del servidor:',
        "  $appKey",
        '  (Solo se usa al crear el .env la primera vez. En actualizaciones, conserva la del servidor.)',
        '',
        'Pasos (detalle en docs/despliegue.md):',
        '  1. Subir y extraer el zip de la aplicacion en /home/USUARIO/superoptica-app',
        '  2. Crear el .env con APP_URL=https://DOMINIO/superoptica y la APP_KEY de arriba',
        '  3. Subir y extraer el zip publico en /home/USUARIO/public_html/superoptica',
        '  4. Enlazar las imagenes una sola vez:',
        '     ln -s /home/USUARIO/superoptica-app/storage/app/public /home/USUARIO/public_html/superoptica/storage',
        '  5. Ejecutar con Trabajos de cron y borrar cada tarea al terminar:',
        '     /opt/cpanel/ea-php83/root/usr/bin/php /home/USUARIO/superoptica-app/artisan migrate --force',
        '     /opt/cpanel/ea-php83/root/usr/bin/php /home/USUARIO/superoptica-app/artisan db:seed --force',
        '     /opt/cpanel/ea-php83/root/usr/bin/php /home/USUARIO/superoptica-app/artisan optimize',
        '',
        'Este script no sube nada ni toca la base de datos.'
    )

    $summary | Set-Content -LiteralPath $summaryPath -Encoding UTF8

    Write-Host ''
    Write-Host 'Paquete listo.' -ForegroundColor Green
    Write-Host "  $appZip"
    Write-Host "  $publicZip"
    Write-Host "  $summaryPath"
    Write-Host ''
    Write-Host 'APP_KEY para el .env del servidor:' -ForegroundColor Yellow
    Write-Host "  $appKey"
} catch {
    Write-Host ''
    Write-Host "ERROR: $($_.Exception.Message)" -ForegroundColor Red
    $exitCode = 1
} finally {
    if (-not $KeepStage) {
        if ($stage) {
            Remove-Item -LiteralPath $stage -Recurse -Force -ErrorAction SilentlyContinue
        }

        if ($publicStage) {
            Remove-Item -LiteralPath $publicStage -Recurse -Force -ErrorAction SilentlyContinue
        }
    } elseif ($stage) {
        Write-Host ''
        Write-Host "Carpetas temporales conservadas: $stage y $publicStage" -ForegroundColor Yellow
    }
}

exit $exitCode
