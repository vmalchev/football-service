<?php

require_once __DIR__ . '/../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);

 $app->withFacades();

// $app->withEloquent();

/*
 * Configuration
 */

$app->configure('app'); // this is not there by default, but we need it due to weird frameword issues otherwise
$app->configure('cors');
$app->configure('smp-cache');

// Initialize an application aspect container
$applicationAspectKernel = \Sportal\FootballApi\Infrastructure\AOP\ApplicationAspectKernel::getInstance();

$applicationAspectKernel->init([
    'debug' => env('GOAOP_DEBUG_MODE', false), // use 'false' for production mode
    'appDir' => base_path(), // Application root directory
    'features' => 128,
    'cacheFileMode' => 0777,
    'cacheDir' => storage_path('smpaspect'), // Cache directory
    'includePaths' => [
        base_path('football-service/src/Application')
    ]
]);

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

// $app->middleware([
//     App\Http\Middleware\ExampleMiddleware::class
// ]);

// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

$app->middleware(Fruitcake\Cors\HandleCors::class);
$app->middleware(\App\Http\Middleware\MetricsMiddleware::class);
$app->middleware(App\Auth\BasicAuthMiddleware::class);
$app->middleware(App\Http\Middleware\LanguageMiddleware::class);


$app->routeMiddleware([
    'processJson' => App\Http\Middleware\ProcessJsonMiddleware::class
]);
$app->routeMiddleware([
    'jsonRequestBody' => App\Http\Middleware\JsonRequestBodyMiddleware::class
]);
$app->routeMiddleware([
    'authorization' => App\Http\Middleware\AuthorizationMiddleware::class
]);
$app->routeMiddleware([
    'pagination' => App\Http\Middleware\PaginationMiddleware::class
]);
$app->routeMiddleware([
    'validationInt' => App\Http\Middleware\ValidationIntMiddleware::class
]);
$app->routeMiddleware([
    'validationUuid' => App\Http\Middleware\ValidationUuidMiddleware::class
]);
$app->routeMiddleware([
    'translation' => App\Http\Middleware\TranslationMiddleware::class
]);
$app->routeMiddleware([
    'matchesCacheMiddleware' => App\Http\Middleware\MatchesCacheMiddleware::class
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

// These are default lumen service providers
// $app->register(App\Providers\AppServiceProvider::class);
// $app->register(App\Providers\AuthServiceProvider::class);
// $app->register(App\Providers\EventServiceProvider::class);

$app->register(App\Providers\DatabaseServiceProvider::class);
$app->register(App\Providers\FeedServiceProvider::class);
$app->register(App\Providers\RepositoryServiceProvider::class);
$app->register(App\Providers\ImportServiceProvider::class);
$app->register(App\Providers\AppServiceProvider::class);
$app->register(Fruitcake\Cors\CorsServiceProvider::class);
$app->register(SMP\CacheControl\Providers\CacheControlServiceProvider::class);
$app->register(App\Providers\BlacklistServiceProvider::class);
$app->register(App\Providers\ValidationServiceProvider::class);
$app->register(App\Providers\SerializerServiceProvider::class);
$app->register(App\Providers\PlayerServiceProvider::class);
$app->register(App\Providers\LanguageServiceProvider::class);
$app->register(App\Providers\TranslationServiceProvider::class);
$app->register(App\Providers\EntityExistsServiceProvider::class);
$app->register(App\Providers\TeamServiceProvider::class);
$app->register(App\Providers\CountryServiceProvider::class);
$app->register(App\Providers\CoachServiceProvider::class);
$app->register(App\Providers\VenueServiceProvider::class);
$app->register(App\Providers\RefereeServiceProvider::class);
$app->register(App\Providers\TeamSquadServiceProvider::class);
$app->register(App\Providers\CityServiceProvider::class);
$app->register(App\Providers\PresidentServiceProvider::class);
$app->register(App\Providers\AssetServiceProvider::class);
$app->register(App\Providers\LineupServiceProvider::class);
$app->register(App\Providers\SeasonServiceProvider::class);
$app->register(App\Providers\TournamentServiceProvider::class);
$app->register(App\Providers\MatchServiceProvider::class);
$app->register(App\Providers\GroupServiceProvider::class);
$app->register(App\Providers\StageServiceProvider::class);
$app->register(App\Providers\StageTeamServiceProvider::class);
$app->register(App\Providers\MatchStatusServiceProvider::class);
$app->register(App\Providers\MatchEventServiceProvider::class);
$app->register(App\Providers\StandingServiceProvider::class);
$app->register(App\Providers\StandingRuleServiceProvider::class);
$app->register(App\Providers\StandingEntryRuleServiceProvider::class);
$app->register(App\Providers\PlayerStatisticServiceProvider::class);
$app->register(App\Providers\EventNotificationServiceProvider::class);
$app->register(App\Providers\KnockoutSchemeServiceProvider::class);
$app->register(App\Providers\TournamentGroupServiceProvider::class);
$app->register(App\Providers\TournamentOrderServiceProvider::class);
$app->register(App\Providers\RoundServiceProvider::class);
$app->register(App\Providers\TournamentGroupSelectionServiceProvider::class);

//AMQP
$app->configure('amqp');
$app->register(Bschmitt\Amqp\LumenServiceProvider::class);


$app->register(\Triadev\PrometheusExporter\Provider\PrometheusExporterServiceProvider::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    require __DIR__ . '/../app/Http/routes.php';
});

$app->router->group([
    'namespace' => 'App\Http\V2\Controllers',
], function ($router) {
    require __DIR__ . '/../app/Http/V2/routes.php';
});

$app->router->get(
    '/metrics',
    \Triadev\PrometheusExporter\Controller\LumenController::class . '@metrics'
);

return $app;