<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {

            //Configuracion para el servicio de passport
            Route::prefix('service/passport')
                ->middleware('passport')
                ->group(base_path('routes/apis/servicio.php'));

            //Configuracion para los servicios de login y usuarios.
            Route::prefix('service/rest/v1/security')
                ->middleware('security')
                ->namespace('App\\Http\\Controllers\\V1\\Seguridad')
                ->group(base_path('routes/apis/v_1/seguridad_s/seguridad_1_1.php'));

            //Configuracion para los servicios de catalogo de la primer version del sistema.
            Route::prefix('service/rest/v1/catalogo')
                ->middleware('catalogo')
                ->namespace('App\\Http\\Controllers\\V1\\Catalogo')
                ->group(base_path('routes/apis/v_1/catalogo_s/catalogo_1_1.php'));

            //Configuracion para los servicios principales de la primer version del sistema.
            Route::prefix('service/rest/v1/principal')
                ->middleware('principal')
                ->namespace('App\\Http\\Controllers\\V1\\Principal')
                ->group(base_path('routes/apis/v_1/principal_s/principal_1_1.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('passport', function (Request $request) {
            return Limit::perMinute(5)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('security', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('catalogo', function (Request $request) {
            return Limit::perMinute(120)->by(optional($request->user())->id ?: $request->ip());
        });

        RateLimiter::for('principal', function (Request $request) {
            return Limit::perMinute(160)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}
