<?php

namespace App\Http;

use App\Providers\RouteServiceProvider;
use Allyson\MultiEnv\Concerns\BootstrappersTrait;
use Illuminate\Foundation\Http\Kernel as LaravelHttpKernel;

class HttpKernel extends LaravelHttpKernel
{
    use BootstrappersTrait;

    /**
     * The application's middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Http\Middleware\TrustProxies::class,
        // \Illuminate\Http\Middleware\HandleCors::class,
        \Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \Illuminate\Foundation\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];

    /**
     * Bootstrap the application for HTTP requests.
     *
     * @return void
     */
    public function bootstrap()
    {
        // In the CreatesApplication trait the HTTP application/bootstrappers were initialized.
        // When doing an HTTP test (Ex. `->get`), in order to simulate the request,
        // it will be necessary to "reload" the bootstrappers because they have already
        // been initialized in the trait above, hence the removal of the `$this- check >app->hasBeenBootstrapped()`.
        // This is not necessary in production `nginx->php->index.php` as the `index.php` file is correct!
        $this->app->bootstrapWith($this->bootstrappers());

        $this->app->register(RouteServiceProvider::class, true);
    }

    /**
     * Returns the bootstrappers that will be executed at the end.
     *
     * @var array<class-string>
     */
    public function afterBootstrappers(): array
    {
        return [
            \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
            \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
            \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
            \Illuminate\Foundation\Bootstrap\BootProviders::class,
        ];
    }
}
