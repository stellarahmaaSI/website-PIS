<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/
if (isset($_ENV['VERCEL_ENV'])) {
    # Belokkan folder cache internal framework ke /tmp
    $_ENV['APP_SERVICES_CACHE'] = '/tmp/services.php';
    $_ENV['APP_PACKAGES_CACHE'] = '/tmp/packages.php';
    $_ENV['APP_CONFIG_CACHE'] = '/tmp/config.php';
    $_ENV['APP_ROUTES_CACHE'] = '/tmp/routes.php';
}

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

if (isset($_ENV['VERCEL_ENV'])) {
    $app->useStoragePath('/tmp');
}

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

if (isset($_ENV['VERCEL_ENV'])) {
    // Belokkan storage utama ke /tmp
    $app->useStoragePath('/tmp');
    
    // Bikin folder untuk cache view secara otomatis di /tmp jika belum ada
    $viewCachePath = '/tmp/framework/views';
    if (!is_dir($viewCachePath)) {
        mkdir($viewCachePath, 0755, true);
    }
    
    // Paksa Laravel menggunakan path baru ini untuk kompilasi Blade
    config(['view.compiled' => $viewCachePath]);
}

return $app;
